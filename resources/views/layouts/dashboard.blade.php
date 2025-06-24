<html>
<head>
    <title> @yield('title') </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ asset('assets/bower/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('assets/bower/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/skins/skin-blue.min.css') }}">
    @yield('header-scripts')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ route('admin.index') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>L</b>G</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Learn</b>German</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            @if(Auth::user()->avatar_image)
                                <img src="{{ asset('images/users/'.Auth::user()->avatar_image) }}" alt="{{ Auth::user()->name }}" class="user-image" />
                            @else
                            <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            @endif
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if(Auth::user()->avatar_image)
                                    <img src="{{ asset('images/users/'.Auth::user()->avatar_image) }}" alt="{{ Auth::user()->name }}" class="img-circle" />
                                @else
                                <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                @endif
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Member since {{ Auth::user()->created_at }}</small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">

                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">

                                <div class="pull-right">
                                    <a href="{{route('user.logout')}}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    @if(Auth::user()->avatar_image)
                        <img src="{{ asset('images/users/'.Auth::user()->avatar_image) }}" alt="{{ Auth::user()->name }}" class="img-circle" />
                    @else
                    <img src="{{ asset('assets/dist/img/avatar5.png') }}" class="img-circle" alt="User Image">
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    @endif
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <!-- Status -->
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">HEADER</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="{{  Route::current()->getName() == 'admin.index'?'active':'' }}"><a
                        href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> <span>Overview</span></a></li>
                <li class="treeview {{  Route::current()->getName() == 'user.index' ||  Route::current()->getName() == 'user.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-user"></i> <span>Users</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                          </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'user.index'? 'active':'' }}"><a
                                href="{{route('user.index')}}">All users</a></li>
                        <li class="{{ Route::current()->getName() == 'user.create'? 'active':'' }}"><a
                                href="{{route('user.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'article.index' ||  Route::current()->getName() == 'article.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-pencil"></i> <span>Articles</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                          </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'article.index'? 'active':'' }}"><a
                                href="{{route('article.index')}}">All articles</a></li>
                        <li class="{{ Route::current()->getName() == 'article.create'? 'active':'' }}"><a
                                href="{{route('article.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'category.index' ||  Route::current()->getName() == 'category.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-tag"></i> <span>Categories</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                          </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'category.index'? 'active':'' }}"><a
                                href="{{route('category.index')}}">All categories</a></li>
                        <li class="{{ Route::current()->getName() == 'category.create'? 'active':'' }}"><a
                                href="{{route('category.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'comment.index' ||  Route::current()->getName() == 'comment.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-comment"></i> <span>Comments</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                          </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'comment.index'? 'active':'' }}"><a
                                href="{{route('comment.index')}}">All comments</a></li>
                        <li class="{{ Route::current()->getName() == 'comment.create'? 'active':'' }}"><a
                                href="{{route('comment.create')}}">Add new</a></li>
                    </ul>
                </li>

                <li class="treeview {{  Route::current()->getName() == 'word.index'
                                          ||  Route::current()->getName() == 'word.create'
                                          ||  Route::current()->getName() == 'word.importants'
                                          ||  Route::current()->getName() == 'word.valids'
                                          ||  Route::current()->getName() == 'word.today'
                                          ||  Route::current()->getName() == 'word.favourites'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-list"></i> <span>Dictionary</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'word.index'? 'active':'' }}"><a
                                href="{{route('word.index')}}">All words</a></li>
                        <li class="{{ Route::current()->getName() == 'word.favourites'? 'active':'' }}"><a
                                href="{{route('word.favourites')}}">Favourite words</a></li>
                        <li class="{{ Route::current()->getName() == 'word.importants'? 'active':'' }}"><a
                                href="{{route('word.importants')}}">Important words</a></li>
                        <li class="{{ Route::current()->getName() == 'word.valids'? 'active':'' }}"><a
                                href="{{route('word.valids')}}">Valid words</a></li>
                        <li class="{{ Route::current()->getName() == 'word.today'? 'active':'' }}"><a
                                href="{{route('word.today')}}">Today words</a></li>
                        <li class="{{ Route::current()->getName() == 'word.create'? 'active':'' }}"><a
                                href="{{route('word.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'grammar.index' ||  Route::current()->getName() == 'grammar.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-book"></i> <span>Grammars</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'grammar.index'? 'active':'' }}"><a
                                href="{{route('grammar.index')}}">All grammars</a></li>
                        <li class="{{ Route::current()->getName() == 'grammar.create'? 'active':'' }}"><a
                                href="{{route('grammar.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'train.index' ||  Route::current()->getName() == 'train.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-bookmark"></i> <span>Trains</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'train.index'? 'active':'' }}"><a
                                href="{{route('train.index')}}">All trains</a></li>
                        <li class="{{ Route::current()->getName() == 'train.create'? 'active':'' }}"><a
                                href="{{route('train.create')}}">Add new</a></li>
                    </ul>
                </li>

                <li class="treeview {{  Route::current()->getName() == 'video.index' ||  Route::current()->getName() == 'video.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-file-video-o"></i> <span>Videos</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'video.index'? 'active':'' }}"><a
                                href="{{route('video.index')}}">All videos</a></li>
                        <li class="{{ Route::current()->getName() == 'video.create'? 'active':'' }}"><a
                                href="{{route('video.create')}}">Add new</a></li>
                    </ul>
                </li>

                <li class="treeview {{  Route::current()->getName() == 'video_slot.index' ||  Route::current()->getName() == 'video_slot.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-file-video-o"></i> <span>Videos Slots</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'video_slot.index'? 'active':'' }}"><a
                                href="{{route('video_slot.index')}}">All slots</a></li>
                        <li class="{{ Route::current()->getName() == 'video_slot.create'? 'active':'' }}"><a
                                href="{{route('video_slot.create')}}">Add new</a></li>
                    </ul>
                </li>
                <li class="treeview {{  Route::current()->getName() == 'slot_category.index' ||  Route::current()->getName() == 'slot_category.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-file-video-o"></i> <span>Slots Category</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'slot_category.index'? 'active':'' }}"><a
                                href="{{route('slot_category.index')}}">All Categories</a></li>
                        <li class="{{ Route::current()->getName() == 'video_slot.create'? 'active':'' }}"><a
                                href="{{route('slot_category.create')}}">Add new</a></li>
                    </ul>
                </li>

                <li class="treeview {{  Route::current()->getName() == 'level.index' ||  Route::current()->getName() == 'level.create'? 'active':'' }} ">
                    <a href="#"><i class="fa fa-sort"></i> <span>Levels</span>
                        <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Route::current()->getName() == 'level.index'? 'active':'' }}"><a
                                href="{{route('level.index')}}">All Levels</a></li>
                        <li class="{{ Route::current()->getName() == 'level.create'? 'active':'' }}"><a
                                href="{{route('level.create')}}">Add new</a></li>
                    </ul>
                </li>

                <li class="{{ Route::current()->getName() == 'option.edit'? 'active':''  }} ">
                    <a href="{{ Route('option.edit') }}"><i class="fa fa-cog"></i> <span>Options</span>

                    </a>

                </li>
                
                
                <li class="{{ Route::current()->getName() == 'bugs.index'? 'active':''  }} ">
                    <a href="{{ Route('bugs.index') }}"><i class="fa fa-bug"></i> <span>Bugs</span>

                    </a>

                </li>
            </ul>


            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('page-header')
                <small>@yield('page-description')</small>
            </h1>
            @yield('breadcrumb')
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
            <!--------------------------
              | Status here |
              -------------------------->
            <div class="row">
                <div class="col-sm-12">
                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('info') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-error alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            @yield('page-content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">

        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; {{ now()->year }}.</strong> All rights reserved.
    </footer>


    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
    immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('assets/bower/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/bower/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

@yield('footer-scripts')
</body>
</html>
