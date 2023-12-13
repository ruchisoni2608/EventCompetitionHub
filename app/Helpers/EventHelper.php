<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Redirect;
use App\Models\Event;

class EventHelper
{  
    public static function checkSelectedEventExistence()
    {
        $selectedEvent = session('selectedEvent');
        if (!$selectedEvent) {
                    return false;
                }

        $event = Event::find($selectedEvent->id);

        return $event ? true : false;
        
    }
}