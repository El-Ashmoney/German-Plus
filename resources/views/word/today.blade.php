@extends('layouts.dashboard')
@section('title', 'Today words')
@section('page-header', 'Today words')
@section('page-description', date('Y-m-d') )
@section('header-scripts')

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style>
  .example-modal .modal {
    position: relative;
    top: auto;
    bottom: auto;
    right: auto;
    left: auto;
    display: block;
    z-index: 1;
  }

  .example-modal .modal {
    background: transparent !important;
  }
</style>
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> overview</a></li>
    <li class="active">Today words</li>
</ol>

@endsection
@section('page-content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Momrize this words</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>German</th>
                <th>Arabic</th>
                <th>English</th>
                <th>Note</th>
                <th>Memorize rank</th>
                <th>Sound</th>
                <th>Image</th>
                @if(Route::currentRouteName() == 'word.today')
                <th>Action</th>
                @endif
              </tr>
              </thead>
              <tbody>
              @foreach ($words as $word)
              <tr>
                <td id="german_td_{{$word->id}}">
                  @if(Route::current()->getName() != 'word.test')
                    {{$word->german}}
                  @else
                
                  <div class="form-group">
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="german_{{$word->id}}" name="german" placeholder="Orignal word">
                          <input type="hidden" class="form-control" id="_german_{{$word->id}}" name="_german" value="{{$word->german}}">
                      </div>
                      <div class="col-sm-3">
                          <button class="btn btn-info pull-right check" data-id="{{$word->id}}">Check</button>
                      </div>
                  </div>
                  
                  @endif  
                </td>
                <td class="arabic">{{ $word->arabic }}</td>
                <td>{{ $word->english }}</td>
                <td>{{ $word->note }}</td>
                <td>{{ $word->memorize_rank }}</td>
                <td>
                  @if(!empty($word->sound))
                  <audio controls>
                    <source src="{{ asset('sounds/'.$word->sound) }}" type="audio/mpeg">
                  </audio>
                  @endif
                </td>
                <td>
                  @if(!empty($word->image))
                    <img src="{{ asset('images/words/'.$word->image) }}" alt="{{ $word->arabic }}" class="img-thumbnail sm-img" />
                  @endif
                </td>
                @if(Route::currentRouteName() == 'word.today')

                <td>
                  <button onclick="set_favourite(this, {{$word->id}})" data-val="{{$word->is_favourite}}"  class="ajax_action btn {{ $word->is_favourite(Auth::user())?'btn-success':'btn-default' }} btn-sm"><i class="fa fa-star"></i> </button>
                  <button onclick="set_important(this, {{$word->id}})" data-val="{{$word->is_important}}" class="ajax_action btn {{ $word->is_important(Auth::user())?'btn-success':'btn-default' }} btn-sm"><i class="fa fa-bookmark"></i> </button>
                  <button onclick="set_valid(this, {{$word->id}})" data-val="{{$word->is_valid}}" class="ajax_action btn {{ $word->is_valid?'btn-success':'btn-default' }}  btn-sm"><i class="fa fa-check"></i> </button>
                <br>
                <br>
                <a href="{{ route('word.edit', $word->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
                <form action="{{ route('word.destroy', $word->id) }}" method="POST" class="delete-form">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </button>
                </form>
                </td>
                @endif
              </tr>
              @endforeach
              </tfoot>
            </table>
            @if( Route::current()->getName() == 'word.today')
              <a href="{{ route('word.test') }}" class="btn btn-default btn-lg">Test on this words <i class="fa fa-angle-right"></i></a>
            @endif
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </div>

</div>

<div class="modal modal-warning fade" id="modal-warning">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Sorry</h4>
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
<script src="{{ asset('assets/bower/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/bower/fastclick/lib/fastclick.js') }}"></script>

<script>
  $('#datatable').DataTable({
    "bPaginate": false,
    "info": false
  })
  function set_important(elem, id) {
    val = $(elem).data('val');
    $.ajax({
        type:'POST',
        url:'{{Route("word.important")}}',
        data: {'id' : id, 'val': val},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success:function(data) {

          if( $(elem).hasClass('btn-success') ){
            $(elem).removeClass('btn-success').addClass('btn-default');
            $(elem).data('val', 0);
          }else if($(elem).hasClass('btn-default') ){
            $(elem).removeClass('btn-default').addClass('btn-success');
            $(elem).data('val', 1);
          }
          $("#modal-success .modal-body p").text(data.msg);
          $("#modal-success").modal('show');
        }
    });
  }

  function set_favourite(elem, id) {
    val = $(elem).data('val');
    $.ajax({
        type:'POST',
        url:'{{Route("word.favourite")}}',
        data: {'id' : id, 'val': val},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success:function(data) {
          
          if( $(elem).hasClass('btn-success') ){
            $(elem).removeClass('btn-success').addClass('btn-default');
            $(elem).data('val', 0);
          }else if($(elem).hasClass('btn-default') ){
            $(elem).removeClass('btn-default').addClass('btn-success');
            $(elem).data('val', 1);
          }
          $("#modal-success .modal-body p").text(data.msg);
          $("#modal-success").modal('show');
        }
    });
  }

  function set_valid(elem, id) {
    val = $(elem).data('val');
    $.ajax({
        type:'POST',
        url:'{{Route("word.valid")}}',
        data: {'id' : id, 'val': val},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success:function(data) {

          if( $(elem).hasClass('btn-success') ){
            $(elem).removeClass('btn-success').addClass('btn-default');
            $(elem).data('val', 0);
          }else if($(elem).hasClass('btn-default') ){
            $(elem).removeClass('btn-default').addClass('btn-success');
            $(elem).data('val', 1);
          }
          $("#modal-success .modal-body p").text(data.msg);
          $("#modal-success").modal('show');
        }
    });
  }
  
  function quick_store() {
    $.ajax({
        type:'POST',
        url:'{{Route("word.quick_store")}}',
        data: $("#quick_add").serialize(),
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success:function(data) {
          $("#modal-success .modal-body p").text(data.msg);
          $("#modal-success").modal('show');
        }
    });
  }
  function quick_trash(elem, id){
    $.ajax({
        type:'POST',
        url:'{{Route("word.quick_trash")}}',
        data: {'id' : id},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success:function(data) {
          $(elem).closest('tr').remove();
          if( $('#datatable tbody tr').length === 0 ){
            $('#datatable tbody').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No matching records found</td></tr>');
          }
          $("#modal-success .modal-body p").text(data.msg);
          $("#modal-success").modal('show');
        }
    });
  }
  function set_memorize(id) {
      $.ajax({
          type:'POST',
          url:'{{Route("word.memorize")}}',
          data: {'id' : id},
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success:function(data) {
            $("#modal-success .modal-body p").text(data.msg);
            $("#modal-success").modal('show');
          }
      });
    }
    $( ".check" ).each(function( i ) {

    $(this).click(function( event ){
      typed = $("#german_"+$(this).data('id') ).val(); // typed
      orginal = $("#_german_"+$(this).data('id')).val(); // orginal
      if(orginal.indexOf(typed) > 0){
        is_alike = true;
      }else{
        is_alike = false;
      }
      if(  orginal === typed ||  is_alike  ){
        $("#german_td_"+$(this).data('id')).html(  orginal+"<br>"+typed );
        set_memorize( $(this).data('id') );
      }else{
        event.preventDefault();
        $("#modal-warning .modal-body p").text('this words do not match');
        $("#modal-warning").modal('show');
      }
    })

    });
</script>
@endsection