@extends('layouts.dashboard')
@section('title', 'All videos')
@section('page-header', 'All videos')
@section('page-description', 'Show all videos')
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
                            <th>German Script</th>
                            <th>Arabic Script</th>
                            <th>Featured Image</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($videos as $video)
                            <tr>
                                <td>{{ $video->video_id }}  <a target="_blank" href="https://www.youtube.com/watch?v={{ $video->video_id }} "><i class="fa fa-external-link"></i></a></td>
                                <td>
                                    <button class="btn btn-primary"
                                            onclick='CopyToClipboard("{{ asset('scripts/de/'.$video->german_script) }}", true, "Value copied")'>
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>

                                <td>
                                    <button class="btn btn-primary"
                                            onclick='CopyToClipboard("{{ asset('scripts/ar/'.$video->arabic_script) }}", true, "Value copied")'>
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>
                                <td>
                                    @if(!empty($video->featured_image))
                                        <img src="{{ asset('images/videos/'.$video->featured_image) }}"
                                             class="img-thumbnail sm-img"/>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('video_slot.create_with_id', $video->id) }}" class="btn btn-primary btn-sm"><i
                                            class="fa fa-edit"></i> Add Slot</a>
                                    <a href="{{ route('video.edit', $video->id) }}" class="btn btn-primary btn-sm"><i
                                            class="fa fa-edit"></i> Edit</a>
                                    <a href="{{ route('video.delete', $video->id) }}" class="btn btn-danger btn-sm"><i
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
