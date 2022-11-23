@extends('Event.layout.app')

@section('content')

@if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
 @endif
 <form action="{{route('event.index')}}" method="get">
                              @csrf
 <div class="container">
  <div class="row">

    <div class="col-sm">

      <div class="form-group">
      <select class="form-control" id="filter_id" name="filter_id">
        @php
        $filter_fields=(config('filter.filter'));
        @endphp
        
        @foreach($filter_fields as $key => $value)
        <option value="{{$key}}">{{ $value}}</option>
        @endforeach 
      
      </select>
      </div>

    </div>
    <div class="col-sm">
    <button class="btn btn-default" type="submit">Filter</button>
    </div>

  </div>
</div>
</form>




<div class="row" style="padding:20px;">
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
Create new</button>
<div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create New Event Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('event.store')}}" method="post">
      @csrf
                        @method('post')

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                </div>
                <input type="text" required name="title" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea3">Description</label>
                <textarea class="form-control" name="description" id="exampleFormControlTextarea3" rows="7"></textarea>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="date" id="start_date" name="start_date" required>     
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" id="end_date" name="end_date" required>     
            </div>
            <button type="submit" class="btn btn-primary">Create</button>      
      </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</div>

<div>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Title</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

  @foreach($events as $event)
    <tr>
      <td>{{$event->title}}</td>
      <td>
      @php
      $now = Carbon::now();
      $date1 = Carbon::createFromFormat('Y-m-d H:i:s', $now);
      $date2 = Carbon::createFromFormat('Y-m-d H:i:s', $event->end_date);
      $status = $date1->gte($date2)?"Finished":"Upcoming";
      @endphp
      {{$status}}

      </td>
      <td>
      <button type="button" id="{{$event->id}}" class="btn btn-primary " data-toggle="modal" data-target="#editmodal{{$event->id}}">Edit</button>


<!-- Modal -->
<div class="modal fade" id="editmodal{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Event Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="{{route('event.update',$event->id)}}" method="post">
      @csrf
                        @method('PUT')
                        

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                </div>
                <input type="text" value="{{$event->title}}" required name="title" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="form-group">
                <label for="exampleFormControlTextarea3">Description</label>
                <textarea class="form-control" name="description" id="exampleFormControlTextarea3" rows="7">{{$event->description}}</textarea>
            </div>

            <div class="form-group">
                <label>Start Date</label>                
                <input type="date" id="start_date" name="start_date" value="{{date('Y-m-d', strtotime($event->start_date))}}" required>     
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{date('Y-m-d', strtotime($event->end_date))}}" required>     
            </div>
            <button type="submit" class="btn btn-primary">Update</button>      
      </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal -->





      <button type="button" id="{{$event->id}}" class="btn btn-danger btn-delete" onclick="return confirm('Are you sure?')" >Delete</button></td>

    </tr>

  @endforeach 

    </tbody>
</table>

</div>


<script>
        $(document).ready(function () {
          $('.btn-delete').on('click', function () {
              var id= this.id;
              $.ajax({
                    type: 'DELETE',
                    url: "/event/"+id,
                    data: {
                        '_token': '{{ @csrf_token() }}',
                        'id': this.id,                       
                    },
                    dataType: 'json',
                    success: function () {
                      window.location.href = "/";
                    }
                })
            })
        });
</script>


@endsection