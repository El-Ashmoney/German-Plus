@extends('layouts.dashboard')
@section('title', 'Words in category')
@section('page-header', 'All words')
@section('page-description', 'Show all words')
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
        <li><a href="{{ route('category.index') }}"><i class="fa fa-dashboard"></i> All categories</a></li>
        <li class="active">Category Words</li>
    </ol>

@endsection
@section('page-content')

    <div class="row">

        <div class="col-xs-12">
            <div class="box box-primary" id="words_list">
                <div class="box-header">
                    <h3 class="box-title">Words</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if( !$words->isEmpty() )
                        <ul class="words-list todo-list">
                            @foreach($words as $word)
                                <li id="word_{{$word->id}}" data-id="{{ $word->id}}">
                                    <!-- drag handle -->
                                    <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                    <span class="text">{{$word->german}} / {{ $word->arabic  }}</span>
                                    <div class="tools">
                                        <a href="{{ route('word.edit', $word->id) }}" class="btn btn-success btn-xs">
                                            Edit
                                            <i class="fa fa-edit modal-edit" data-title="{{$word->title}}" data-id="{{$word->id}}"></i>
                                        </a>
                                        <form action="{{ route('word.destroy', $word->id) }}" method="POST" class="delete-form">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="todo-list">
                        </ul>
                        <p class="no_msg">No words</p>
                    @endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix no-border">
                    <button type="button" class="btn btn-default pull-right" onclick="save_order()">Save</button>
                </div>
            </div>
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
    <script src="{{ asset('assets/bower/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>

        // jQuery UI sortable
        $('.words-list').sortable({
            placeholder         : 'sort-highlight',
            handle              : '.handle',
            forcePlaceholderSize: true,
            zIndex              : 999999,
        });

        function save_order(){
            var words_order = [];
            $('.words-list li').each(function (index){
                words_order.push({'index': index, 'id': $(this).data('id')});
            });
            $.ajax({
                type:'POST',
                url:'{{Route("category.save_words_order")}}',
                data: {'orders': words_order},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data) {

                }
            });
        }

    </script>
@endsection
