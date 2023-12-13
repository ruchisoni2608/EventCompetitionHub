<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    public function index()
{
    $events = Event::all();
  //  dd($events);
    $judges=User::where('type',1)->orderBy('name','asc')->get();
    return view('admin.eventindex', compact('events','judges'));
}
 
public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'assigned_judges' => 'required|array',
    ]);

    $assignedJudges = json_encode($validatedData['assigned_judges']);

      $start_date = date('Y-m-d', strtotime($validatedData['start_date']));
    $end_date = date('Y-m-d', strtotime($validatedData['end_date']));

    // Create the event
    $event = new Event();
    $event->name = $validatedData['name'];
    $event->start_date = $start_date;
    $event->end_date = $end_date;
    $event->assigned_judges = $assignedJudges;
    $event->save();

    return redirect()->route('home')->with('success', 'Event created successfully.');
}

public function edit(Event $event)
{
    return view('admin.events.edit', compact('event'));
}


public function update(Request $request, Event $event)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'assigned_judges' => 'required|array',
    ]);

    $assignedJudges = json_encode($validatedData['assigned_judges']);

    $event->name = $validatedData['name'];
    $event->start_date = $validatedData['start_date'];
    $event->end_date = $validatedData['end_date'];
    $event->assigned_judges = $assignedJudges;
    $event->save();

    // Update session data
    session([
        'selectedEvent' => $event,
        'selectedEventName' => $event->name,
        'selectedEventStartDate' => $event->start_date,
        'selectedEventEndDate' => $event->end_date,
    ]);
  session(['eventDataUpdated' => true]);
    return redirect()->route('admin.eventindex')->with('success', 'Event updated successfully.');
}


public function destroy(Event $event)
{
    
    $event->delete();
    //   session([
    //     'selectedEvent' => null,
    //     'selectedEventName' => null,
    //     'selectedEventStartDate' => null,
    //     'selectedEventEndDate' => null,
    // ]);
   
    return redirect()->route('admin.eventindex')->with('success', 'Event deleted successfully.');
    
}

//as admin delete event that status check for event
// public function checkEventStatus()
// {
//     $eventExists = Event::where('id', session('selectedEvent.id'))->exists();

//     if (!$eventExists) {
//         $userType = trim(strtolower(Auth::user()->type));
        
//         if ($userType == "manager" || $userType == 1) {
//              //   dd($eventExists,$userType);
//             return response()->json(['status' => 'deleted', 'redirect' => route('manager.home')]);
//         } else {
//             return response()->json(['status' => 'deleted', 'redirect' => route('home')]);
//         }
//     }

//     return response()->json(['status' => 'active']);
// }



}