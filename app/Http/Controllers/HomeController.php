<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Helpers\EventHelper;
use Illuminate\Support\Facades\Redirect;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $judges=User::where('type',1)->orderBy('name','asc')->get();       
        $events = Event::all(); 
        return view('home',compact('judges','events'));
    }
    public function adminHome()
    {
        $judges=User::where('type',1)->orderBy('name','asc')->get();       
        $events = Event::all(); 
        return view('home',compact('judges','events'));
    }
    public function admindashboard()
    {
        if (!session()->has('selectedEvent')) {
            return redirect()->route('home')->with('error', 'Please select an event first.');
        }
        return view('admindashboard');
    }
    public function layoutapp()
    {        
        return view('layouts.app');
    }  
    
    public function managerHome()
    {
        $user = Auth::user();    
        $events = Event::all(); 
        $assignedEvents = Event::where('assigned_judges', 'LIKE', '%"'.$user->id.'"%')->get();
    
        //dd($user, $assignedEvents);       
        return view('managerHome', compact('assignedEvents','events'));
    }
    public function managerdashboard()
    {
         if (!session()->has('selectedEvent')) {
            return redirect()->route('manager.home')->with('error', 'Please select an event first.');
        }
         if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
        return view('managerdashboard');
    }

    public function storeSelectedEvent(Request $request)
    {        
        $selected = $request->input('event');
        $event = Event::find($selected);
    //     $request->session()->put('selectedEvent', $event);
        if ($event) {
            session([
                'selectedEvent' => $event,
                'selectedEventName' => $event->name,
                'selectedEventStartDate' => $event->start_date,
                'selectedEventEndDate' => $event->end_date,
            ]);
        }

        return response()->json(['event' => $event->name]);
    }

}