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
        <link rel="stylesheet" href="{{ asset('css/simple.css') }}">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue">
        <div class="wrapper">
            @yield('page-content')
        </div>
        <!-- ./wrapper -->

          <!-- REQUIRED JS SCRIPTS -->

          <!-- jQuery 3 -->
          <script src="{{ asset('assets/bower/jquery/dist/jquery.min.js') }}"></script>
          <!-- Bootstrap 3.3.7 -->
          <script src="{{ asset('assets/bower/bootstrap/dist/js/bootstrap.min.js') }}"></script>
          <!-- AdminLTE App -->
          <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

          <!-- Optionally, you can add Slimscroll and FastClick plugins.
               Both of these plugins are recommended to enhance the
               user experience. -->


    </body>
</html>
