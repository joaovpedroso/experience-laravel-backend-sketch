<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>Login @ </title>

    <meta content="noindex, nofollow" name="robots" />

    <!-- BEGIN PLUGIN CSS -->
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/animate.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END PLUGIN CSS -->

    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="{{ asset('webarch/css/webarch.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('webarch/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <!-- END CORE CSS FRAMEWORK -->
  </head>
  <!-- END HEAD -->

  <!-- BEGIN BODY -->
  <body class="gradient-bg no-top">

  <div class="login-logo">
    <h2>Logo</h2>
  </div>

  <!-- BEGIN CONTAINER -->
  <div class="container">
    <div class="row login-centered column-seperation">
      <div class="system hidden-sm hidden-xs">
        <i class="fa fa-lock"></i> Painel de Controle
      </div>

      @yield('content')

    </div>
  </div>
  <!-- END CONTAINER -->

  <!-- CORE JS FRAMEWORK-->
  <script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>

  <!-- JS DEPENDECENCIES-->
  <script src="{{ asset('assets/plugins/jquery/jquery-1.11.3.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/bootstrapv3/js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/jquery-block-ui/jqueryblockui.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js') }}" type="text/javascript"></script>
  <!-- <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script> -->
  <script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>

  <!-- CORE TEMPLATE JS -->
  <script src="{{ asset('webarch/js/webarch.js') }}" type="text/javascript"></script>

  <!-- PAGE JS -->
  @yield('js')
  </body>
</html>
