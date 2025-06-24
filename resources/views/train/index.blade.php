@extends('layouts.dashboard')
@section('title', 'All trains')
@section('page-header', 'All Trains')
@section('page-description', 'Show all trains')
@section('header-scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

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
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Overview</a></li>
    <li class="active">All trains</li>
</ol>

@endsection
@section('page-content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Search word</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Type word</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" id="quick_search" placeholder="Type word and hit Enter ...">
                        </div>
                    </div>
                    <div class="divider-dashed"></div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3">Select Categry</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="quick_category_filter">
                            <option value="">Choose category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="row">

    <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">All trains</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="datatable" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Word id</th>
                <th>German</th>
                <th>Arabic</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($trains as $train)
              <tr id="train_{{$train->id}}">
                <td>{{ $train->word->id }}</td>
                <td>{{ $train->word->german }}</td>
                <td>{{ $train->word->arabic }}</td>
                <td>
                <a href="{{ route('train.edit', $train->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>
                <form action="{{ route('train.destroy', $train->id) }}" method="POST" class="delete-form">
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
        {{ $trains->onEachSide(5)->links() }}


    </div>

</div>


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
<script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>

<script>
    $('#datatable').DataTable({
      "paginate": false,
      "info": false
    })

    function quick_search(elem) {
        keyword = $(elem).val();
        $.ajax({
            type: 'POST',
            url: '{{Route("train.quick_search")}}',
            data: {'keyword': keyword},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                render_search_result(data);
            }
        });
    }

    function quick_category_filter(elem) {
        category_id = $(elem).val();
        $.ajax({
            type: 'POST',
            url: '{{Route("train.quick_category_filter")}}',
            data: {'category_id': category_id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                render_search_result(data);
            }
        });
    }

    function render_search_result(data) {
        $('.pagination').remove();

        if (data.length === 0) {
            $('#datatable tbody').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No matching records found</td></tr>');
            return;
        }
        var html = '';
        for (var i = 0; i < data.length; i++) {
            for(var x = 0; x < data[i].trains.length; x++) {
                html += '<tr>' +
                    '<td>' + data[i].id + '</td>' +
                    '<td class="arabic">';
                if (data[i].german !== null) {
                    html += data[i].german;
                }
                html += '</td><td>';
    
                if (data[i].arabic !== null) {
                    html += data[i].arabic;
                }
                html += '</td>';
    
    
    
                html += '<td>';
    
                html += '<a title="Train edit" href="/admin/train/' + data[i].trains[x].id + '/edit/" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>';
                html += '<button title="Delete train" onclick="quick_trash(this,' + data[i].trains[x].id + ')" data-val="' + data[i].trains[x].is_valid + '"  class="ajax_action btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
    
                html += '</td>';
    
                html += '</tr>';
            }
        }
        $('#datatable tbody').html(html);
    }

    $("#quick_search").keypress(function (e) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            if ($(this).val() != '') {
                quick_search(this);
            } else {
                $("#modal-success .modal-body p").text('This field can not be empty');
                $("#modal-success").modal('show');

            }
        }
    });
    $("#quick_category_filter").change(function (e) {
        if ($(this).val() != '') {
            quick_category_filter(this);
        }
    });


    function quick_trash(elem, id) {
        $.ajax({
            type: 'POST',
            url: '{{Route("train.quick_trash")}}',
            data: {'id': id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $(elem).closest('tr').remove();
                if ($('#datatable tbody tr').length === 0) {
                    $('#datatable tbody').html('<tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">No matching records found</td></tr>');
                }
                $("#modal-success .modal-body p").text(data.msg);
                $("#modal-success").modal('show');
            }
        });
    }
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })


  </script>
@endsection
