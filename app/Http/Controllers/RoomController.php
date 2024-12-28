<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use App\Models\Room;
    use Illuminate\Http\Request;

    class RoomController extends Controller
    {
        public function index(Request $request)
        {
            /* price */
            $min_price = $request->query("min") ? $request->query("min") : 1;
            $max_price = $request->query("max") ? $request->query("max") : 999999;

            /* order */
            $o_column = "";
            $o_order = "";
            $order = $request->query("order") ? $request->query("order") : -1;
            switch ($order) {
                case 1:
                    $o_column = 'created_at';
                    $o_order = "DESC";
                    break;
                case 2:
                    $o_column = 'created_at';
                    $o_order = "ASC";
                    break;
                case 3:
                    $o_column = 'regular_price';
                    $o_order = "ASC";
                    break;
                case 4:
                    $o_column = 'regular_price';
                    $o_order = "DESC";
                    break;
                default:
                    $o_column = 'id';
                    $o_order = "DESC";
                    break;
            }

            /* category */
            $f_categories = $request->query("categories");
            $categories = Category::orderBy('name', 'ASC')->get();

            /* size */
            $size = $request->query("size") ? $request->query("size") : 12;

            /* rooms */
            $rooms = Room::where(function ($query) use ($f_categories) {
                $query->whereIn("category_id", explode(",", $f_categories))->orWhereRaw("'" . $f_categories . "'=''");
            })->where(function ($query) use($min_price, $max_price) {
                $query->whereBetween("regular_price", [$min_price, $max_price])->orWhereBetween("sale_price",
                    [$min_price, $max_price]);
            })->orderBy($o_column, $o_order)->paginate(12);

            return view("room", compact(["rooms", "size", "order", "categories", "f_categories", "min_price", "max_price"]));
        }

        public function room_detail($room_slug)
        {
            $room = Room::where("slug", $room_slug)->first();
            $related_rooms = Room::where("slug", "<>", $room_slug)->get()->take(8);
            return view("detail", compact(["room", "related_rooms"]));
        }
    }
