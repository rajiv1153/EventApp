<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Services\EventService; 
use Carbon\Carbon;

class EventController extends Controller
{
   
    public function __construct()
    {
        $this->service = (new EventService());
    }

    public function index(Request $request){
        $filer_id=($_GET['filter_id'])??null;
        $events =  $this->service->getIndexData($filer_id);
        return view('Event.index',compact('events'));
    }

    public function store(EventRequest $request) 
    {       
        $data=$request;
        $this->service->store($data);
        return redirect()->route('event.index')->withErrors('success','Event Created.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if (isset($event) && $event != null) {
            $event->delete();
        }
        return response()->json(['success'=>'Event Deleted Successfully!']);
    }

    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        if (isset($event) && $event != null) {
            $input = $request;
            $this->service->update($input,$id);
        }
        return redirect()->route('event.index')->withErrors('success','Event Updated.');
    }    
}
