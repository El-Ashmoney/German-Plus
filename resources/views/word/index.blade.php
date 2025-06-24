@extends('layouts.dashboard')
@section('title', 'All words')
@section('page-header', 'All words')
@section('page-description', 'Show all words')
@section('header-scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
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
        <li class="active">All words</li>
    </ol>

@endsection
@section('page-content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Add word</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <!-- form start -->
                    <form class="form-horizontal" action="javascript:void(0)" id="quick_add" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="german" class="col-sm-3 control-label">German word</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="german" name="german"
                                           placeholder="German word">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="arabic" class="col-sm-3 control-label">Arabic word</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="arabic" name="arabic"
                                           placeholder="Arabic word">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="english" class="col-sm-3 control-label">English word</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="english" name="english"
                                           placeholder="English word">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="note" class="col-sm-3 control-label">Note</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="note" name="note"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" checked name="is_valid" class="flat-red">
                                        <strong> Is completed</strong>
                                    </label>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" onclick="quick_store()" class="btn btn-info pull-right">Quick add
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                    @include('partials.formerrors')
                </div>
            </div>
        </div>

    </div>

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
                            <input type="text" class="form-control" id="quick_search"
                                   placeholder="Type word and hit Enter ...">
                        </div>
                    </div>
                    <div class="divider-dashed"></div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3">Select Categry</label>
                        <div class="col-md-9 col-sm-9 ">
                            <select class="form-control" id="quick_category_filter">
                                <option value="">Choose category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
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
                    <h3 class="box-title">All Words</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="datatable" class="table table-bordered table-hover"
                    ">
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
                                    <img src="{{ asset('images/words/'.$word->image) }}" alt="{{ $word->arabic }}"
                                         class="img-thumbnail sm-img"/>
                                @endif
                            </td>
                            <td>
                                <button title="Set Favourite" onclick="set_favourite(this, {{$word->id}})"
                                        data-val="{{$word->is_favourite}}"
                                        class="ajax_action btn {{ $word->is_favourite(Auth::user())?'btn-success':'btn-default' }} btn-sm">
                                    <i class="fa fa-star"></i></button>
                                <button title="Set important" onclick="set_important(this, {{$word->id}})"
                                        data-val="{{$word->is_important}}"
                                        class="ajax_action btn {{ $word->is_important(Auth::user())?'btn-success':'btn-default' }} btn-sm">
                                    <i class="fa fa-bookmark"></i></button>
                                <button title="Set valid word" onclick="set_valid(this, {{$word->id}})"
                                        data-val="{{$word->is_valid}}"
                                        class="ajax_action btn {{ $word->is_valid?'btn-success':'btn-default' }}  btn-sm">
                                    <i class="fa fa-check"></i></button>
                                <br>
                                <br>
                                <a href="{{ route('word.edit', $word->id) }}" title="Edit word"
                                   class="btn btn-primary btn-sm"><i
                                        class="fa fa-edit"></i> </a>
                                <form action="{{ route('word.destroy', $word->id) }}" method="POST" class="delete-form">
                                    @method('DELETE')
                                    @csrf
                                    <button title="Delete word" type="submit" class="btn btn-danger btn-sm"><i
                                            class="fa fa-trash"></i>
                                    </button>
                                </form>
                                <a title="Create train" href="{{ route('train.create_with_id', $word->id) }}"
                                   class="btn btn-primary btn-sm"><i
                                        class="fa fa-bookmark"></i> </a>
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
            "info": false,
            "scrollX": true
        })

        function set_important(elem, id) {
            val = $(elem).data('val');
            $.ajax({
                type: 'POST',
                url: '{{Route("word.important")}}',
                data: {'id': id, 'val': val},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if ($(elem).hasClass('btn-success')) {
                        $(elem).removeClass('btn-success').addClass('btn-default');
                        $(elem).data('val', 0);
                    } else if ($(elem).hasClass('btn-default')) {
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
                type: 'POST',
                url: '{{Route("word.favourite")}}',
                data: {'id': id, 'val': val},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if ($(elem).hasClass('btn-success')) {
                        $(elem).removeClass('btn-success').addClass('btn-default');
                        $(elem).data('val', 0);
                    } else if ($(elem).hasClass('btn-default')) {
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
                type: 'POST',
                url: '{{Route("word.valid")}}',
                data: {'id': id, 'val': val},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if ($(elem).hasClass('btn-success')) {
                        $(elem).removeClass('btn-success').addClass('btn-default');
                        $(elem).data('val', 0);
                    } else if ($(elem).hasClass('btn-default')) {
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
                type: 'POST',
                url: '{{Route("word.quick_store")}}',
                data: $("#quick_add").serialize(),
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $("#modal-success .modal-body p").text(data.msg);
                    $("#modal-success").modal('show');
                }
            });
        }

        function quick_trash(elem, id) {
            $.ajax({
                type: 'POST',
                url: '{{Route("word.quick_trash")}}',
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

        function quick_search(elem) {
            keyword = $(elem).val();
            $.ajax({
                type: 'POST',
                url: '{{Route("word.quick_search")}}',
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
                url: '{{Route("word.quick_category_filter")}}',
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
                html += '<tr>' +
                    '<td>' + data[i].german + '</td>' +
                    '<td class="arabic">';
                if (data[i].arabic !== null) {
                    html += data[i].arabic;
                }
                html += '</td><td>';

                if (data[i].english !== null) {
                    html += data[i].english;
                }
                html += '</td><td>';

                if (data[i].note !== null) {
                    html += data[i].note;
                }
                html += '</td>' +

                    '<td>' + data[i].memorize_rank + '</td>';
                html += '<td>';
                if (data[i].sound_url) {

                    sound = '<audio controls="">' +
                        '<source src="' + data[i].sound_url + '" type="audio/mpeg">' +
                        '</audio>';
                    html += sound;

                }
                html += '</td>';
                html += '<td>';

                if (data[i].image) {
                    html += '<img src="' + data[i].image_url + ' " alt=" ' + data[i].german + ' " class="img-thumbnail sm-img" />';
                }
                html += '</td>';

                if (data[i].is_favourite === 0) {
                    btn_class_favourite = 'btn-default';
                } else {
                    btn_class_favourite = 'btn-success';
                }
                if (data[i].is_important === 0) {
                    btn_class_important = 'btn-default';
                } else {
                    btn_class_important = 'btn-success';
                }
                if (data[i].is_valid === 0) {
                    btn_class_valid = 'btn-default';
                } else {
                    btn_class_valid = 'btn-success';
                }
                html += '</td>';
                html += '<td>';
                html += '<button onclick="set_favourite(this,' + data[i].id + ')" data-val="' + data[i].is_favourite + '"  class="ajax_action btn ' + btn_class_favourite + ' btn-sm"><i class="fa fa-star"></i></button>';
                html += '<button onclick="set_important(this,' + data[i].id + ')" data-val="' + data[i].is_important + '"  class="ajax_action btn ' + btn_class_important + ' btn-sm"><i class="fa fa-bookmark"></i></button>';
                html += '<button onclick="set_valid(this,' + data[i].id + ')" data-val="' + data[i].is_valid + '"  class="ajax_action btn ' + btn_class_valid + ' btn-sm"><i class="fa fa-check"></i></button>';
                html += '<br><br>';

                html += '<a href="/admin/word/' + data[i].id + '/edit/" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> </a>';
                html += '<button onclick="quick_trash(this,' + data[i].id + ')" data-val="' + data[i].is_valid + '"  class="ajax_action btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                html += '<a href="/admin/train/create/' + data[i].id + '" class="btn btn-primary btn-sm"><i class="fa fa-bookmark"></i> </a>';

                html += '</td>';

                html += '</tr>';
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
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })


    </script>
@endsection
