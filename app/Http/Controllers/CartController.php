<?php

    namespace App\Http\Controllers;

    use App\Models\Address;
    use App\Models\Coupon;
    use App\Models\OrderItem;
    use App\Models\Room;
    use App\Models\Transaction;
    use App\Models\Cart as CartModel;
    use Illuminate\Http\Request;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Surfsidemedia\Shoppingcart\Facades\Cart;

    class CartController extends Controller
    {
        public function index(Request $request)
        {
            if ($request->query('pay') == 'success') {
                Cart::instance("cart")->destroy();
            }

            $items = Cart::instance('cart')->content();
            $coupons = Coupon::all();

            return view("cart", compact(["items", "coupons"]));
        }

        public function add_to_cart(Request $request)
        {
            Cart::instance("cart")->add($request->id, $request->name, $request->quantity, $request->price, [$request->category_id])->associate("App\Models\Room");
            return redirect()->back();
        }

        public function increase_cart_quantity($rowId)
        {
            $room = Cart::instance("cart")->get($rowId);
            $qty = $room->qty + 1;
            Cart::instance("cart")->update($rowId, $qty);
            return redirect()->back();
        }

        public function decrease_cart_quantity($rowId)
        {
            $room = Cart::instance("cart")->get($rowId);
            $qty = $room->qty - 1;
            Cart::instance("cart")->update($rowId, $qty);
            return redirect()->back();
        }

        public function remove_item($rowId)
        {
            Cart::instance("cart")->remove($rowId);
            return redirect()->back();
        }

        public function empty_cart()
        {
            Cart::instance("cart")->destroy();
            return redirect()->back();
        }

        public function apply_coupon_code(Request $request)
        {
            $coupon_code = $request->coupon_code;
            if (isset($coupon_code)) {
                $coupon = Coupon::where("code", $coupon_code)->where("expiry_date", ">=", Carbon::today())->where("cart_value", "<=", Cart::instance("cart")->subtotal())->first();
                if (!$coupon) {
                    return redirect()->back()->with("error", "Invalid coupon code!!!");
                } else {
                    Session::put("coupon", ["code" => $coupon->code, "type" => $coupon->type, "value" => $coupon->value, "cart_value" => $coupon->cart_value,]);
                    $this->calculateDiscount();
                    return redirect()->back()->with('success', "Coupon has been applied");
                }
            } else {
                return redirect()->back()->with('error', "Invalid coupon code");
            }
        }

        public function calculateDiscount()
        {
            $discount = 0;
            if (Session::has("coupon")) {
                if (Session::get("coupon")['type'] == "fixed") {
                    $discount = Session::get("coupon")['value'];
                } else {
                    $discount = (Cart::instance("cart")->subtotal() * Session::get("coupon")["value"]) / 100;
                }
                $subtotalAfterDiscount = Cart::instance("cart")->subtotal() - $discount;
                $taxAfterDiscount = ($subtotalAfterDiscount * config("cart.tax")) / 100;
                $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

                Session::put("discounts", ["discount" => number_format(floatval($discount), 2, '.', ','), "subtotal" => number_format(floatval($subtotalAfterDiscount), 2, '.', ','), "tax" => number_format(floatval($taxAfterDiscount), 2, '.', ','), "total" => number_format(floatval($totalAfterDiscount), 2, '.', ','),]);
            }
        }

        public function remove_coupon_code()
        {
            Session::forget("coupon");
            Session::forget("discounts");
            return back()->with('success', "Coupon has been removed");
        }

        public function checkout()
        {
            if (!Auth::check()) {
                return redirect()->route("login");
            }
            $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
            return view('checkout', compact("address"));
        }

        public function place_order(Request $request)
        {
            $user_id = Auth::user()->id;

            $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
            if (!$address) {
                $request->validate(['name' => 'required|max:100', 'phone' => 'required|numeric|digits:10', 'zip' => 'required|numeric|digits:6', 'state' => 'required', 'city' => 'required', 'address' => 'required', 'locality' => 'required', 'landmark' => 'required']);

                $address = new Address();
                $address->user_id = $user_id;
                $address->name = $request->name;
                $address->phone = $request->phone;
                $address->zip = $request->zip;
                $address->state = $request->state;
                $address->city = $request->city;
                $address->address = $request->address;
                $address->locality = $request->locality;
                $address->landmark = $request->landmark;
                $address->country = '';
                $address->isdefault = true;
                $address->save();
            }

            $this->setAmountForCheckout();

            $order = new Order();
            $order->user_id = $user_id;
            $order->subtotal = floatval(Session::get('checkout')['subtotal']);
            $order->discount = floatval(Session::get('checkout')['discount']);
            $order->tax = floatval(Session::get('checkout')['tax']);
            $order->total = floatval(Session::get('checkout')['total']);
            $order->name = $address->name;
            $order->phone = $address->phone;
            $order->locality = $address->locality;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->state = $address->state;
            $order->country = $address->country;
            $order->landmark = $address->landmark;
            $order->zip = $address->zip;
            $order->save();

            foreach (Cart::instance('cart')->content() as $item) {
                $orderitem = new OrderItem();
                $orderitem->room_id = $item->id;
                $orderitem->order_id = $order->id;
                $orderitem->price = $item->price;
                $orderitem->quantity = $item->qty;
//                $orderitem->category = $item->category;
                $orderitem->save();
            }

            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();

            Cart::instance('cart')->destroy();
            Session::forget('checkout');
            Session::forget('coupon');
            Session::forget('discounts');
            return redirect()->route('cart.confirmation');
        }

        public function setAmountForCheckout()
        {
            if (!Cart::instance('cart')->count() > 0) {
                Session::forget('checkout');
                return;
            }

            if (Session::has('coupon')) {
                Session::put('checkout', ['discount' => Session::get('discounts')['discount'], 'subtotal' => Session::get('discounts')['subtotal'], 'tax' => Session::get('discounts')['tax'], 'total' => Session::get('discounts')['total']]);
            } else {
                Session::put('checkout', ['discount' => 0, 'subtotal' => Cart::instance('cart')->subtotal(), 'tax' => Cart::instance('cart')->tax(), 'total' => Cart::instance('cart')->total()]);
            }
        }

        public function confirmation()
        {
            return view('order-confirmation');
        }

        public function store(Request $request)
        {
            $cart = new CartModel();
            $cart->vat = 10;
            $cart->user_id = Auth::user()->id;
            $cart->room_id = $request->id;
//            $cart->coupon_id = $request->coupon_id;

            $cart->save();
            return redirect()->back();
        }

        public function xxxx()
        {
            $items = CartModel::all();
            dd($items);
            $coupons = Coupon::all();
            return view("cart", compact(["items", "coupons"]));
        }
    }
