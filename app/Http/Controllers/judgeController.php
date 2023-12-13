<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participate;
use App\Models\Ranking;
use App\Models\User;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use App\Helpers\EventHelper;
use Illuminate\Support\Facades\Redirect;



class judgeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

  
    public function index()
    {
        if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
          $selectedEvent = session('selectedEvent');

    if (!$selectedEvent) {
        return redirect()->route('home')->with('error', 'Please select an event.');
    }
        $today = Carbon::today(); 
        $participate = Participate::orderBy('name', 'asc')->get(); 
        $isrank= Ranking::whereDate('created_at', $today)
                             ->where('event_id', $selectedEvent->id)
                             ->pluck('userid');

        $rankings = Ranking::whereDate('created_at', $today)
                                ->where('event_id', $selectedEvent->id)
                                ->get();   
        $rankingNames = [];
        foreach ($rankings as $ranking) {
            $participantId = $ranking->userid;
            $assigningName = $ranking->updated_by ? User::find($ranking->updated_by)->name : User::find($ranking->created_by)->name;
            $rankingNames[$participantId] = $assigningName;            
        }
         return view('judge.index',compact('participate','isrank','rankingNames'));
    }    


    public function store(Request $request)
    {
      //dd($request->all());
        $request->validate([
            'userid' => 'required',
        ]);
     
        $selectedEvent = session('selectedEvent');

        if ($selectedEvent) {
            $event_id = $selectedEvent->id;
            $input = $request->all();
           
        $blacklist = $request->has('blacklist') ? (int)$request->input('blacklist') : 0;

        if ($blacklist === 1) {
           
            $input['costume'] = 0;
            $input['skill'] = 0;
            $input['punctual'] = 0;
        } 

        $input['blacklist'] = $blacklist;


            $input['event_id'] = $event_id;      

           
            $today = Carbon::today();
            $existingRanking = Ranking::where('userid', $request->userid)
                ->whereDate('created_at', $today)
                ->first();
            if ($existingRanking) {
                $input['updated_by'] = auth()->user()->id;
                $existingRanking->update($input);
                // session([
                //     'selectedEventStartDate' => $updatedStartDate,
                //     'selectedEventEndDate' => $updatedEndDate,
                // ]);
                return redirect()->route('judge.index')->with('success', 'Participate Ranking Data updated successfully.');
            } else {
                $input['created_by'] = auth()->user()->id;
                Ranking::create($input);
                // session([
                //     'selectedEventStartDate' => $newStartDate,
                //     'selectedEventEndDate' => $newEndDate,
                // ]);
                return redirect()->route('judge.index')->with('success', 'Participate Ranking Data created successfully.');
        }
        } else {
            
            return redirect()->route('judge.index')->with('error', 'No selected event found.');
        }
    }



 public function show(Participate $participates,$resource)
{
    if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
    $id = Hashids::decode($resource)[0];

    if (empty($id)) {
        return redirect()->route('home')->with('error', 'Invalid ID');
    }

    $participates = Participate::find($id);

    if (!$participates) {
        return redirect()->route('home')->with('error', 'Participate not found');
    }

    $selectedEvent = session('selectedEvent');
    $today = Carbon::today();
    $existingRanking = Ranking::where('userid', $participates->id)
        ->whereDate('created_at', $today)
        ->where('event_id', $selectedEvent->id)
        ->first();

    return view('judge.show', compact('participates', 'existingRanking'));
}


    // public function show(Participate $participates,$id)
    // {
        
    //       $selectedEvent = session('selectedEvent');

    //     if (!$selectedEvent) {
    //         return redirect()->route('home')->with('error', 'Please select an event.');
    //     }

    //    $participates = Participate::find($id);

    //     $userId = $participates->id;
    //      $today = Carbon::today(); 
    //     $existingRanking = Ranking::where('userid', $userId)->whereDate('created_at', $today)->where('event_id', $selectedEvent->id)->first();
       
    //     if (!$existingRanking) {
    //         $existingRanking = null;
    //     }

    //     //dd($existingRanking);    
    //     return view('judge.show',compact('participates','existingRanking'));
    // }
 




   
    public function edit(Participate $participate)
    {
        if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
         return view('judge.edit',compact('participate'));
    }
   
    
    public function destroy($pid, Ranking $rankings)
    {
        $today = Carbon::today();
        $participate = Ranking::where('userid', $pid)->whereDate('created_at', $today)->first();

        if ($participate) {
            $participate->delete();
            return redirect()->route('judge.index')->with('success', 'Ranking Data Deleted successfully');
        } else {
            return redirect()->route('judge.index')->with('error', 'Ranking Data not found or already deleted');
        }
    }



    







   
 // public function destroy($pid,Ranking $rankings)
    // {
    //    // dd($pid);
    //       $today = Carbon::today(); 
    //     $participate = Ranking::where('userid',$pid)->whereDate('created_at', $today)->first();
    //    //dd($participate);
    //     $participate->delete();
    //     //  $participate->each->delete();     
    //     return redirect()->route('judge.index')
    //                     ->with('success','Ranking Data Deleted successfully');
    
    // }
 
    // public function store(Request $request)
    // {
    //  //   dd($request->all());
    //     $request->validate([
    //         'userid' => 'required',           
    //         'costume' => 'required',
    //         'skill' => 'required',           
    //         'punctual' => 'required',            
    //     ]);
   
    //     $input = $request->all();    
    //     $today = Carbon::today(); 
    //     $existingRanking = Ranking::where('userid', $request->userid)->whereDate('created_at', $today)->first();
        
    //         if ($existingRanking)
    //         {
    //             $input['updated_by'] = auth()->user()->id;
    //     //   dd($existingRanking);
    //         $existingRanking->update($input);
    //             return redirect()->route('judge.index')->with('success', 'Participate Ranking Data updated successfully.');
    //         } else {
    //              $input['created_by'] = auth()->user()->id;
    //         Ranking::create($input);
    //             return redirect()->route('judge.index')->with('success', 'Participate Ranking Data created successfully.');
    //         }      
    // }


     
     
    
}