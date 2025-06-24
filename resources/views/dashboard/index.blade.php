@extends('layouts.dashboard')
@section('title', 'Overview')
@section('page-header', 'Overview')
@section('page-description', 'Highlights')
@section('header-scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/bower/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> overview</a></li>
    <li class="active">All comments</li>
</ol>

@endsection
@section('page-content')

<div class="row">

    <div class="col-xs-6">
         <!-- TO DO List -->
         <div class="box box-primary" id="todo_list">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">To Do List</h3>

              <div class="box-tools pull-right">
               {{ $todos->onEachSide(5)->links() }}
              </div>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              @if( !$todos->isEmpty() )
              <ul class="todo-list">
                @foreach($todos as $todo)
                <li class="{{$todo->is_done?'done':''}}" id="todo_{{$todo->id}}">
                  <!-- drag handle -->
                  <span class="handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                  </span>
                  <!-- checkbox -->
                  <input type="checkbox" {{$todo->is_done?'checked':''}} id="check_todo" data-id="{{$todo->id}}" onchange="check_todo(this)" >
                  <!-- todo text -->
                  <span class="text">{{$todo->title}}</span>
                  <!-- Emphasis label -->
                  <small class="label label-{{$todo->color_since}}"><i class="fa fa-clock-o"></i> {{ $todo->created_since }}</small>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit modal-edit" data-title="{{$todo->title}}" data-id="{{$todo->id}}" onclick="modal_edit(this)"></i>
                    <i class="fa fa-trash-o" data-id="{{$todo->id}}" onclick="delete_todo(this)"></i>
                  </div>
                </li>
                @endforeach
              </ul>
              @else
                <ul class="todo-list">
                </ul>
                <p class="no_msg">No to do items</p>  
              @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#modal-todo"><i class="fa fa-plus"></i> Add item</button>
            </div>
          </div>
          <!-- /.box -->
    </div>
    <div class="col-xs-6">
         <div class="box box-primary">
           <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Users</h3>


          </div>
          <div class="box-body tile_count">
            <div class="row">
                <div class="col-sm-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i>Total Users</span>
                    <div class="count green">{{ $users_total }}</div>
                </div>
                <div class="col-sm-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-user"></i>New Users Today</span>
                    <div class="count green">{{ $users_today }}</div>
                </div>
                
            </div>
          </div>

        </div>
    </div>
</div>
<div class="modal fade" id="modal-todo">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Todo</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" placeholder="Enter ..." id="todo_value">
              </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="add_todo()" data-dismiss="modal">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <div class="modal fade" id="modal-todo-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Update Todo</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" placeholder="Enter ..." id="todo_value">
                <input type="hidden" id="todo_id">
              </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="update_todo(this)" data-dismiss="modal" id="update_todo">Save changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal modal-success fade" id="modal-success">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Success</h4>
          </div>
          <div class="modal-body">
            <p></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection



@section('footer-scripts')
<script src="{{ asset('assets/bower/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/bower/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/bower/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $('#articles').DataTable()
    $('.pagination').addClass('pagination-sm inline');
    $('.pagination .page-link').click(function(e){
      e.preventDefault();
      $('.pagination li').removeClass('active');
      $('.pagination li').removeAttr('aria-current');
      $(this).parent('li').addClass('active');
      $(this).parent('li').attr('aria-current', 'page');
      var myurl = $(this).attr('href');
      if (typeof myurl === "undefined") {
        page = $(this).html();
      }else{
        page = $(this).attr('href').split('page=')[1];

      }
      paginate_todo(page);
    })
    
    function paginate_todo(page){
        $.ajax(
        {
          type: "GET",
          url: '?page=' + page,
          datatype: "html"
        }).done(function(data){
            todo_list = $(data).find('.todo-list').html();
            $(".todo-list").empty().html(todo_list);
            //location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }

    function add_todo() {
      title = $("#todo_value").val();
      $.ajax({
          type:'POST',
          url:'{{Route("todo.store")}}',
          data: {'title' : title},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(data) {
            
            $('.todo-list').show();
            $('.no_msg').remove();

            $("#todo_value").val('');
            var new_item = '<li id="todo_'+data.id+'">'+
            '                    <!-- drag handle -->'+
            '                    <span class="handle">'+
            '                      <i class="fa fa-ellipsis-v"></i>'+
            '                      <i class="fa fa-ellipsis-v"></i>'+
            '                    </span>'+
            '                    <!-- checkbox -->'+
            '                    <input type="checkbox" id="check_todo" data-id="'+data.id+'" onchange="check_todo(this)" >'+
            '                    <!-- todo text -->'+
            '                    <span class="text">'+data.title+'</span>'+
            '                    <!-- Emphasis label -->'+
            '                    <small class="label label-success"><i class="fa fa-clock-o"></i> Just now</small>'+
            '                    <!-- General tools such as edit or delete-->'+
            '                    <div class="tools">'+
            '                      <i class="fa fa-edit modal-edit" data-title="'+data.title+'" data-id="'+data.id+'" onclick="modal_edit(this)"></i>'+
            '                      <i class="fa fa-trash-o modal-trash" data-id="'+data.id+'" onclick="delete_todo(this)" ></i>'+
            '                    </div>'+
            '                  </li>';
              


            $('.todo-list').append(new_item);
          }
      });
    }
    function check_todo(elem){
      is_done=0;
      if($(elem).is(":checked")) {
        is_done=1;
      }
      id = $(elem).data('id');
      $.ajax({
          type:'POST',
          url:'{{Route("todo.done")}}',
          data: {'is_done' : is_done, 'id' : id},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(data) {

          }
      });
    }

    function update_todo(elem){
      title = $('#modal-todo-edit').find('#todo_value').val();
      id = $('#modal-todo-edit').find('#todo_id').val();
      $.ajax({
          type:'POST',
          url:'{{Route("todo.quick_update")}}',
          data: {'title' : title, 'id' : id},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(data) {
            $('#todo_'+id).find('.text').text(title);
          }
      });
    }

    function delete_todo(elem){
      id = $(elem).data('id');
      $.ajax({
          type:'POST',
          url:'{{Route("todo.quick_destroy")}}',
          data: {'id' : id},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(data) {
            $('#todo_'+id).remove();
            if($('.todo-list li').length == 0){
              $('.todo-list').hide();
              $('.todo-list').closest('.box-body').append('<p class="no_msg">No to do items</p>');
            }
          }
      });
    }
    function modal_edit(elem){
      title = $(elem).data('title');
      $('#modal-todo-edit').find('#todo_id').val($(elem).data('id'));
      $('#modal-todo-edit').find('#todo_value').val(title);
      $('#modal-todo-edit').modal('show');
    }
  
</script>
<script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script>
<script src="{{ asset('assets/dist/js/demo.js') }}"></script>

@endsection