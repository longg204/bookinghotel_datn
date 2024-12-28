<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Coupon;
    use App\Models\Order;
    use App\Models\OrderItem;
    use App\Models\Room;
    use App\Models\Slide;
    use App\Models\Transaction;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Str;
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

    class AdminController extends Controller
    {
        public function index()
        {
//            $orders = Order::orderBy("created_at", "DESC")->take(10);
//            $dashboardDatas = DB::select("select sum(total) as TotalAmount,
//                                                sum(if(status = 'ordered', total, 0)) as TotalOrderedAmount,
//                                                sum(if(status = 'delivered', total, 0)) as TotalDeliveredAmount,
//                                                sum(if(status = 'caceled', total, 0)) as TotalCanceledAmount,
//                                                count(*) as Total,
//                                                sum(if(status = 'ordered',1, 0)) as TotalOrdered,
//                                                sum(if(status = 'delivered', 1, 0)) as TotalDelivered,
//                                                sum(if(status = 'caceled', 1, 0)) as TotalCanceled
//                                                from orders");
//            dd(Auth::getUser());
//            $user = User::find('id', Auth::getUser());
            return view('admin.index', compact([]));
        }


        /* category */
        public function category()
        {
            $categories = Category::query()->orderBy('id', 'desc')->paginate(10);
            return view('admin.category', compact('categories'));
        }

        public function category_add()
        {
            return view('admin.category-add');
        }

        public function category_store(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:categories,slug', 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $category = new Category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumdnailImage($image, $file_name);
            $category->image = $file_name;
            $category->save();
            return redirect()->route('admin.category')->with('status', 'Category has been added succesfully');
        }

        public function GenerateCategoryThumdnailImage($image, $imageName)
        {
            $destinationPath = public_path('uploads/category');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(124, 124, 'top');
            $img->resize(124, 124)->save($destinationPath . '/' . $imageName);
        }

        public function category_edit($id)
        {
            $category = Category::find($id);
            return view('admin.category-edit', compact('category'));
        }

        public function category_update(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:categories,slug,' . $request->id, 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            if ($request->hasFile('image')) {
                if (File::exists(public_path('uploads/category') . '/' . $category->image)) {
                    File::delete(public_path('uploads/category') . '/' . $category->image);
                }
                $image = $request->file('image');
                $file_extension = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateCategoryThumdnailImage($image, $file_name);
                $category->image = $file_name;
            }
            $category->save();
            return redirect()->route('admin.category')->with('status', 'Category has been edited succesfully');
        }

        public function category_delete($id)
        {
            $category = Category::find($id);
            if (File::exists(public_path('uploads/category') . '/' . $category->image)) {
                File::delete(public_path('uploads/category') . '/' . $category->image);
            }
            $category->delete();
            return redirect()->route('admin.category')->with('status', 'Category has been deleted succesfully');
        }

        /* room */
        public function room()
        {
            $rooms = Room::query()->orderBy('id', 'desc')->paginate(10);
            return view('admin.room', compact('rooms'));
        }

        public function room_add()
        {
            $categories = Category::all();
            return view('admin.room-add', compact([ "categories"]));
        }

        public function room_store(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:rooms,slug', 'image' => 'mimes:png,jpg,jpeg|max:2048', "short_description" => "required", "description" => "required", "regular_price" => "required", "sale_price" => "required", "stock_status" => "required", "featured" => "required", "quantity" => "required", "category_id" => "required",]);

            $room = new Room();
            $room->name = $request->name;
            $room->slug = Str::slug($request->name);
            $room->short_description = $request->short_description;
            $room->description = $request->description;
            $room->regular_price = $request->regular_price;
            $room->sale_price = $request->sale_price;
            $room->stock_status = $request->stock_status;
            $room->featured = $request->featured;
            $room->quantity = $request->quantity;
            $room->category_id = $request->category_id;
            $current_timestamp = Carbon::now()->timestamp;

            if ($request->hasFile("image")) {
                $image = $request->file("image");
                $imageName = $current_timestamp . "." . $image->extension();
                $this->GenerateRoomThumdnailImage($image, $imageName);
                $room->image = $imageName;
            }

            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            if ($request->hasFile("images")) {
                $allowFileExtension = ["jpg", "jpeg", "png"];
                $files = $request->file("images");
                foreach ($files as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowFileExtension);
                    if ($gcheck) {
                        $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                        $this->GenerateRoomThumdnailImage($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter += 1;
                    }
                }
                $gallery_images = implode(",", $gallery_arr);
            }
            $room->images = $gallery_images;

            $room->save();
            return redirect()->route('admin.room')->with('status', 'Room has been added succesfully');
        }

        public function GenerateRoomThumdnailImage($image, $imageName)
        {
            $destinationPathThumbnail = public_path('uploads/room/thumbnails');
            $destinationPath = public_path('uploads/room');

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());

            //            $img->cover(540, 690, 'top');
            $img->save($destinationPath . '/' . $imageName);

            $img->save($destinationPathThumbnail . '/' . $imageName);
        }

        public function room_edit($id)
        {
            $categories = Category::all();
            $room = Room::find($id);
            return view('admin.room-edit', compact(['room', 'categories']));
        }

        public function room_update(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:rooms,slug,' . $request->id, 'image' => 'mimes:png,jpg,jpeg|max:2048', "short_description" => "required", "description" => "required", "regular_price" => "required", "sale_price" => "required", "stock_status" => "required", "featured" => "required", "quantity" => "required", "category_id" => "required",]);

            $room = Room::find($request->id);
            $room->name = $request->name;
            $room->slug = Str::slug($request->name);
            $room->short_description = $request->short_description;
            $room->description = $request->description;
            $room->regular_price = $request->regular_price;
            $room->sale_price = $request->sale_price;
            $room->stock_status = $request->stock_status;
            $room->featured = $request->featured;
            $room->quantity = $request->quantity;
            $room->category_id = $request->category_id;
            $current_timestamp = Carbon::now()->timestamp;

            if ($request->hasFile("image")) {
                if (File::exists(public_path('uploads/room') . '/' . $room->image)) {
                    File::delete(public_path('uploads/room') . '/' . $room->image);
                }
                if (File::exists(public_path('uploads/room/thumbnails') . '/' . $room->image)) {
                    File::delete(public_path('uploads/room.thumbnails') . '/' . $room->image);
                }
                $image = $request->file("image");
                $imageName = $current_timestamp . "." . $image->extension();
                $this->GenerateRoomThumdnailImage($image, $imageName);
                $room->image = $imageName;
            }

            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            if ($request->hasFile("images")) {
                foreach (explode(",", $room->images) as $ofile) {
                    if (File::exists(public_path('uploads/room') . '/' . $ofile)) {
                        File::delete(public_path('uploads/room') . '/' . $ofile);
                    }
                    if (File::exists(public_path('uploads/room/thumbnails') . '/' . $ofile)) {
                        File::delete(public_path('uploads/room.thumbnails') . '/' . $ofile);
                    }
                }

                $allowFileExtension = ["jpg", "jpeg", "png"];
                $files = $request->file("images");
                foreach ($files as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowFileExtension);
                    if ($gcheck) {
                        $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                        $this->GenerateRoomThumdnailImage($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter += 1;
                    }
                }
                $gallery_images = implode(",", $gallery_arr);
            }
            $room->images = $gallery_images;

            $room->save();
            return redirect()->route('admin.room')->with('status', 'Room has been edited succesfully');
        }

        public function room_delete($id)
        {
            $room = Room::find($id);
            if (File::exists(public_path('uploads/room/thumbnails') . '/' . $room->image)) {
                File::delete(public_path('uploads/room/thumbnails') . '/' . $room->image);
            }
            if ($room->images) {
                foreach (explode(",", $room->images) as $img) {
                    if (File::exists(public_path('uploads/room') . '/' . $img)) {
                        File::delete(public_path('uploads/room') . '/' . $img);
                    }
                }
            }
            $room->delete();
            return redirect()->route('admin.room')->with('status', 'Room has been deleted succesfully');
        }

        /* coupon */
        public function coupons()
        {
            $coupons = Coupon::orderBy("expiry_date", "DESC")->paginate(12);
            return view("admin.coupon", compact(["coupons"]));
        }

        public function coupon_add()
        {
            return view("admin.coupon-add");
        }

        public function coupon_store(Request $request)
        {
            $request->validate(['type' => 'nullable', 'code' => 'required|unique:coupons,code', 'value' => 'required', 'cart_value' => 'required', 'expiry_date' => 'required']);

            $coupon = new Coupon();
            $coupon->code = $request->code;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->cart_value = $request->cart_value;
            $coupon->expiry_date = $request->expiry_date;

            $coupon->save();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been added succesfully');
        }

        public function coupon_edit($id)
        {
            $coupon = Coupon::find($id);
            return view('admin.coupon-edit', compact('coupon'));
        }

        public function coupon_update(Request $request)
        {
            $request->validate(['type' => 'nullable', 'code' => 'required|unique:coupons,code,' . $request->id, 'value' => 'required', 'cart_value' => 'required', 'expiry_date' => 'required']);

            $coupon = Coupon::find($request->id);
            $coupon->code = $request->code;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->cart_value = $request->cart_value;
            $coupon->expiry_date = $request->expiry_date;

            $coupon->save();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been edited succesfully');
        }

        public function coupon_delete($id)
        {
            $coupon = Coupon::find($id);
            $coupon->delete();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been deleted succesfully');
        }

        /* orders */
        public function orders()
        {
            $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
            return view("admin.orders", compact('orders'));
        }

        public function order_items($order_id)
        {
            $order = Order::find($order_id);
            $orderitems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view("admin.order-details", compact('order', 'orderitems', 'transaction'));
        }

        public function update_order_status(Request $request)
        {
            $order = Order::find($request->order_id);
            $order->status = $request->order_status;
            if ($request->order_status == 'delivered') {
                $order->delivered_date = Carbon::now();
            } else if ($request->order_status == 'canceled') {
                $order->canceled_date = Carbon::now();
            }
            $order->save();
            if ($request->order_status == 'delivered') {
                $transaction = Transaction::where('order_id', $request->order_id)->first();
                $transaction->status = "approved";
                $transaction->save();
            }
            return back()->with("status", "Status changed successfully!");
        }

        public function slides()
        {
            $slides = Slide::orderBy("id", "DESC")->paginate(12);
            return view("admin.slides", compact(["slides"]));
        }

        public function slide_add()
        {
            return view('admin.slide-add');
        }

        public function slide_store(Request $request)
        {
            $request->validate(['tagline' => 'required', 'title' => 'required', 'image' => 'mimes:png,jpg,jpeg|max:2048', 'subtitle' => 'required', 'link' => 'required']);

            $slide = new Slide();
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;

            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateSlideThumdnailImage($image, $file_name);
            $slide->image = $file_name;
            $slide->save();
            return redirect()->route('admin.slides')->with('status', 'Slide has been added succesfully');
        }

        public function GenerateSlideThumdnailImage($image, $imageName)
        {
            $destinationPath = public_path('uploads/slide');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(500, 500, 'top');
            $img->save($destinationPath . '/' . $imageName);
        }

        public function slide_edit($id)
        {
            $slide = Slide::find($id);
            return view('admin.slide-edit', compact('slide'));
        }

        public function slide_update(Request $request)
        {
            $request->validate(['tagline' => 'required', 'title' => 'required', 'image' => 'mimes:png,jpg,jpeg|max:2048', 'subtitle' => 'required', 'link' => 'required']);

            $slide = Slide::find($request->id);
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;

            if ($request->hasFile('image')) {
                if (File::exists(public_path('uploads/slide') . '/' . $slide->image)) {
                    File::delete(public_path('uploads/slide') . '/' . $slide->image);
                }
                $image = $request->file('image');
                $file_extension = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateSlideThumdnailImage($image, $file_name);
                $slide->image = $file_name;
            }
            $slide->save();
            return redirect()->route('admin.slides')->with('status', 'Slide has been edited succesfully');
        }

        public function slide_delete($id)
        {
            $slide = Slide::find($id);
            if (File::exists(public_path('uploads/slide') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slide') . '/' . $slide->image);
            }
            $slide->delete();
            return redirect()->route('admin.slides')->with('status', 'Slide has been deleted succesfully');
        }









        /* ----------------------user--------------------------------------- */
        /* ----------------------user--------------------------------------- */

        public function users()
        {
            $users = User::where('utype','USR')->orderBy("id", "DESC")->paginate(12);
            return view("admin.users", compact(["users"]));
        }


//        public function user_edit($id)
//        {
//            $users = User::find($id);
//            return view('admin.users-edit', compact('users'));
//        }

//        public function user_update(Request $request)
//        {
//            $request->validate(['name' => 'required','email' => 'required|unique:users,code', 'phone_number' => 'required|unique:users,code', 'email' => 'required']);
//
//            $user = User::find($request->id);
//            $user->name = $request->name;
//            $user->email = $request->email;
//            $user->phone_number = $request->phone_number;
//
//            $user->save();
//            return redirect()->route('admin.users')->with('status', 'User has been edited succesfully');
//        }

        public function user_delete($id)
        {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('admin.users')->with('status', 'User has been deleted successfully');
        }
    }

























