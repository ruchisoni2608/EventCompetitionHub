<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participate;
use App\Models\Ranking;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use App\Helpers\EventHelper;
use Illuminate\Support\Facades\Redirect;


class ParticipateController extends Controller
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

            $today = Carbon::today(); 
            $participate = Participate::orderBy('name', 'asc')->get();   
            $isrank = Ranking::whereDate('created_at', $today)
                                ->where('event_id', $selectedEvent->id)
                                ->pluck('userid');
        
            $rankings = Ranking::whereDate('created_at', $today) 
                                ->where('event_id', $selectedEvent->id)
                                ->get();
        //dd($isrank,$rankings);
        $rankingNames = [];
        foreach ($rankings as $ranking) {
            $participantId = $ranking->userid;
        // dd($ranking->updated_by);
            $assigningName = $ranking->updated_by ? User::find($ranking->updated_by)->name : User::find($ranking->created_by)->name;
            $rankingNames[$participantId] = $assigningName;
            
        }
            return view('participate.index',compact('participate','isrank','rankingNames'));
    }

    public function create()
    {
        return view('participate.create');
    }
    public function participateindex()
    {
        $participate = Participate::orderBy('name', 'asc')->get();  
        return view('participate.participateindex',compact('participate'));
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',           
            'image' => 'required|image',
            'dob'=>'required',
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
        }
    
        Participate::create($input);     
        return redirect()->route('participateindex')
                        ->with('success','Participate Data created successfully.');
    
    }

    // public function edit(Participate $participate)
    // {
    //     return view('participate.edit',compact('participate'));
    // }
   
public function edit($resource)
{
   $id = Hashids::decode($resource)[0];
  
    if ($id === null) {
        return redirect()->route('participateindex')->with('error', 'Invalid ID');
    }

    $participate = Participate::find($id);

    if (!$participate) {
       
        return redirect()->route('participateindex')->with('error', 'Participate not found');
    }

    return view('participate.edit', compact('participate'));
}
  
    public function update(Request $request,Participate $participate)
    {
          $request->validate([
            'name' => 'required',
        ]);
  
        $input = $request->all();
  
        if ($image = $request->file('image')) {
            $destinationPath = 'image/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "$profileImage";
            
            $oldImagePath = public_path('image/' . $participate->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
          
       $participate->update($input);
    
        return redirect()->route('participateindex')
                        ->with('success','participate Data updated successfully');   
    }

   
    public function showDailyRanking() 
    {
        if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
        $selectedEvent = session('selectedEvent');
      

        $selectedEventStartDate = session('selectedEventStartDate');
        $selectedEventEndDate = session('selectedEventEndDate');
        $currentDate = Carbon::now()->toDateString();
        $noEventMessage = null;
        $rankedParticipants = null; 
        
         if ($selectedEvent) {
            if ($currentDate >= $selectedEventStartDate && $currentDate <= $selectedEventEndDate) {
                $rankedParticipants = DB::table('ranking')
                    ->select('ranking.userid', 'participates.name', 'participates.dob', DB::raw('SUM(costume + skill + punctual) as total_score'))
                    ->join('participates', 'ranking.userid', '=', 'participates.id')
                    ->whereDate('ranking.created_at', $currentDate)
                    ->whereBetween(DB::raw('DATE(ranking.created_at)'), [$selectedEventStartDate, $selectedEventEndDate])
                    ->where('event_id', $selectedEvent->id)
                    ->groupBy('ranking.userid', 'participates.name', 'participates.dob')
                    ->having('total_score', '>', 0)
                    ->orderByDesc('total_score')
                    ->orderByDesc('participates.dob')
                    ->get();
            } else {
               $rankedParticipants = collect(); 
              // bcz in index we used @if ($rankedParticipants->isEmpty())  &isEmpty() function use on collection and as records maybe null also so that's why use collect() that take always a collection, even if it's an empty one.
            }
          } else {
               
                $noEventMessage = 'Please select an event.';
         }
        return view('participate.daily_ranking', compact('rankedParticipants', 'noEventMessage'));
    }
    public function finalRanking() {

         if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
         $selectedEvent = session('selectedEvent');
        $selectedEventStartDate = session('selectedEventStartDate');
        $selectedEventEndDate = session('selectedEventEndDate');
        
        $currentDate = Carbon::now()->toDateString();

         if ($currentDate >= $selectedEventStartDate && $currentDate <= $selectedEventEndDate) 
      
        {
            $topWinners = DB::table('ranking')
                ->select('ranking.userid', 'participates.name', 'participates.dob')
                ->selectSub(function ($query) use ($selectedEvent,$selectedEventStartDate, $selectedEventEndDate) {
                    $query->select(DB::raw('SUM(costume + skill + punctual)'))
                        ->from('ranking as r')
                        ->whereColumn('r.userid', 'ranking.userid')
                        ->where('r.event_id', $selectedEvent->id)
                        ->whereBetween(DB::raw('DATE(r.created_at)'), [$selectedEventStartDate, $selectedEventEndDate]);
                }, 'total_score')
                ->where('event_id', $selectedEvent->id)
                ->join('participates', 'ranking.userid', '=', 'participates.id')
                ->groupBy('ranking.userid', 'participates.name', 'participates.dob')
                ->having('total_score', '>', 0)
                ->orderByDesc('total_score')
                ->orderByDesc('participates.dob')
                ->limit(3)
                ->get();
         } else {
                 $topWinners = collect(); 
            }
    //dd($topWinners,$selectedEventStartDate);
        return view('participate.final_ranking', compact('topWinners'));
    }    

    

    public function AllDaysRank()
    {
         if (!EventHelper::checkSelectedEventExistence()) {
        return Redirect::to('/home')->with('error', 'Please Select Another Event,This Event No Longer Exist.');
    }
        $selectedEvent = session('selectedEvent');
        $selectedEventStartDate = session('selectedEventStartDate');
        $selectedEventEndDate = session('selectedEventEndDate');
        
        $dates = [];
        $currentDate = $selectedEventStartDate;

        while (strtotime($currentDate) <= strtotime($selectedEventEndDate)) {
            $dates[] = $currentDate;
            $currentDate = date("Y-m-d", strtotime($currentDate . ' + 1 day'));
        }

        $top3Rank = [];

        foreach ($dates as $date) {
            $rankings = DB::table('ranking')
                ->whereDate('ranking.created_at', $date)
                 ->where('event_id', $selectedEvent->id)
                ->select('ranking.userid', 'participates.name', 'participates.dob', DB::raw('SUM(costume + skill + punctual) as total_score'))
                ->join('participates', 'ranking.userid', '=', 'participates.id')
                ->groupBy('ranking.userid', 'participates.name', 'participates.dob')
                ->having('total_score', '>', 0)
                ->orderByDesc('total_score')
                ->orderByDesc('participates.dob')
                ->limit(3)
                ->get();

            $top3Rank[$date] = $rankings;
        }
        //dd($top3Rank);

        return view('participate.AllDaysRank', compact('top3Rank'));
    }
    

    
    public function destroy(Participate $participate)
    {
        $imagePath = public_path('image/' . $participate->image);

        if (file_exists($imagePath)) {
            unlink($imagePath); 
        }
          $participate->delete();
     
        return redirect()->route('participateindex')
                        ->with('success','Data Deleted successfully');
    
    }









}