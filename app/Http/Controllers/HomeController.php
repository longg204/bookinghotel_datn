<?php

namespace App\Http\Controllers;

use App\Models\Contact;
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

    public function contact()
    {
        return(view('contact'));
    }

    public function contact_store(Request $request)
    {
        $request ->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone_number' => 'required|numeric|digits:10',
            'comment' => 'required'
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone_number = $request->phone_number;
        $contact->comment = $request->comment;
        $contact->save();
        return redirect()->back()->with('success', 'Your massage has been sent successfully');
    }
}
