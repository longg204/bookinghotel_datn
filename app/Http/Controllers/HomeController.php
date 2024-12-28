<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Brand;
use App\Models\Category;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $rooms = Room::paginate(12);
        $hotdeals = Room::query()
        ->where('featured', true)
        ->paginate(12);

        $slides = Slide::where("status", 1)->get()->take(3);
        return view('index', compact(["slides","rooms","hotdeals"]));
    }
}
