@extends('layouts.dashboard')
@section('title', 'All video slots')
@section('page-header', 'All slots')
@section('page-description', 'Show all slots')
@section('header-scripts')

    <link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> overview</a></li>
        <li class="active">All videos</li>
    </ol>

@endsection
@section('page-content')

    <div class="row">

        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">videos info</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="videos" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Video ID</th>
                            <td>Youtube</td>
                            <th>Featured Sentence</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($video_slots as $video_slot)
                            <tr>
                                <td>{{ $video_slot->video->id }}</td>
                                <td>{{ $video_slot->video->video_id }}  <a target="_blank" href="https://www.youtube.com/watch?v={{ $video_slot->video->video_id }} "><i class="fa fa-external-link"></i></a></td>
                                <td>
                                  {{$video_slot->featured_sentence}}
                                </td>

                                <td>
                                  {{$video_slot->start_time}}
                                </td>
                                <td>
                                    {{$video_slot->end_time}}
                                </td>
                                <td>
                                    <a href="{{ route('video_slot.edit', $video_slot->id) }}" class="btn btn-primary btn-sm"><i
                                            class="fa fa-edit"></i> Edit</a>
                                    <a href="{{ route('video_slot.delete', $video_slot->id) }}" class="btn btn-danger btn-sm"><i
                                            class="fa fa-edit"></i> Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

        </div>

    </div>


@endsection



@section('footer-scripts')
    <script src="{{ asset('assets/bower/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script>

        // $(document).ready(function () {
        //     $("button").click(function (event) {
        //         event.preventDefault();
        //         CopyToClipboard("This is some test value.", true, "Value copied");
        //     });
        // });

        function CopyToClipboard(value, showNotification, notificationText) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();

            if (typeof showNotification === 'undefined') {
                showNotification = true;
            }
            if (typeof notificationText === 'undefined') {
                notificationText = "Copied to clipboard";
            }

            var notificationTag = $("div.copy-notification");
            if (showNotification && notificationTag.length == 0) {
                notificationTag = $("<div/>", {"class": "copy-notification", text: notificationText});
                $("body").append(notificationTag);

                notificationTag.fadeIn("slow", function () {
                    setTimeout(function () {
                        notificationTag.fadeOut("slow", function () {
                            notificationTag.remove();
                        });
                    }, 1000);
                });
            }
        }
    </script>
    <script>
        $(function () {
            $('#videos').DataTable()
        })
    </script>
@endsection
