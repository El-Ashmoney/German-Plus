@extends('layouts.dashboard')
@section('title', 'Important words')
@section('page-header', 'Important words')
@section('page-description', 'Show Important words')
@section('header-scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Overview</a></li>
    <li class="active">Important words</li>
</ol>

@endsection
@section('page-content')


<div class="row">
    
    <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">All Words</h3>
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
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($words as $word)
              <tr id="word_{{$word->id}}">
                <td>{{ $word->german }}</td>
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
                <td>               
                  <button onclick="set_favourite(this, {{$word->id}})" data-val="{{$word->is_favourite(Auth::user())}}"  class="ajax_action btn {{ $word->is_favourite(Auth::user())?'btn-success':'btn-default' }} btn-sm"><i class="fa fa-star"></i> </button>
                  <button onclick="set_important(this, {{$word->id}})" data-val="{{$word->is_important(Auth::user())}}" class="ajax_action btn {{ $word->is_important(Auth::user())?'btn-success':'btn-default' }} btn-sm"><i class="fa fa-bookmark"></i> </button>
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
              </tr>
              @endforeach
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
        {{ $words->onEachSide(5)->links() }}


    </div>

</div>


@endsection



@section('footer-scripts')
<script src="{{ asset('assets/bower/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
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
          $(elem).closest('tr').remove();
          if( $('#datatable tbody tr').length === 0 ){
            $('#datatable tbody').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No matching records found</td></tr>');
          }
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
          $(elem).closest('tr').remove();
          if( $('#datatable tbody tr').length === 0 ){
            $('#datatable tbody').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No matching records found</td></tr>');
          }
          
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
 
</script>
@endsection