<?php
namespace App\Services; 
use App\Models\Event;
use Carbon\Carbon;
class EventService { 

    public function getIndexData($filer_id)
    {
     $query = Event::query();
     $now=(Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now() ));

     if ($filer_id==1) { //finished
         $query->where('end_date', '<=',  $now );
     }
     elseif ($filer_id==2) { //upcoming
          $query->where('end_date', '>=',  $now );
     }
     elseif ($filer_id==3) { //upcoming within coming 7 days
          $seven_days_ahead=(date('Y-m-d H:i:s', strtotime($now. ' + 7 days')));
          $query->whereBetween('end_date', [$now, $seven_days_ahead] );
     }
     if ($filer_id==4) { //finished events of last 7 days
          $seven_days_back=(date('Y-m-d H:i:s', strtotime($now. ' - 7 days')));
          $query->whereBetween('end_date', [$seven_days_back, $now] );
     }
     $events = $query->orderBy('start_date')->get();
     return $events;
    }
    
    public function store($data)
    {
         $data1=[
        'title'=>$data['title'],
        'description'=>$data['description'],
        'start_date'=>$data['start_date'],
        'end_date'=>$data['end_date'],        
         ];
         Event::create($data1);
         return true;
    }

    public function update($data,$id)
    {
        $event = Event::findOrFail($id);
         $data1=[
        'title'=>$data['title'],
        'description'=>$data['description'],
        'start_date'=>$data['start_date'],
        'end_date'=>$data['end_date'],        
         ];
         $event->update($data1);  
         return true;
    }


}