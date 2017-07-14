<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>

    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-path" content="{{ url('/') }}">

    {{-- CSS TEMPLATE --}}
    <link href="{{ asset('assets/plugins/pace/pace-theme-flash.min.css') }}" rel="stylesheet" type="text/css"
          media="screen"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrapv3/css/bootstrap-theme.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/animate.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('webarch/css/webarch.css') }}" rel="stylesheet" type="text/css"/>
    {{-- CSS PERSONAL --}}
    <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/cropper/cropper.min.css') }}" rel="stylesheet">
    <link href="{{ asset(elixir('css/app.custom.css')) }}" rel="stylesheet" type="text/css"/>

    {{-- BEGIN PAGE CSS --}}
    @yield('css')
</head>

<body class="">
{{-- HEADER --}}
<div class="header navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="header-seperation">

            {{-- MOBILE HEADER --}}
            <ul class="nav pull-left notifcation-center visible-xs visible-sm">
                <li class="dropdown">
                    <a href="#main-menu" data-webarch="toggle-left-side">
                        <div class="iconset top-menu-toggle-white"></div>
                    </a>
                </li>
            </ul>

            {{-- LOGO --}}
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/prestige-white.png') }}" style="margin-left: 37px; margin-top: 20px;" class="logo" alt=""  height="25"/>
            </a>

            {{-- NAV BUTTONS --}}
            <ul class="nav pull-right notifcation-center">
                <li class="dropdown hidden-xs hidden-sm">
                    <a href="/"  class="dropdown-toggle active" data-toggle="">
                        <div class="iconset top-home"></div>
                    </a>
                </li>
                <li class="dropdown visible-xs visible-sm">
                    <a href="#" data-webarch="toggle-right-side">
                        <div class="iconset top-chat-white"></div>
                    </a>
                </li>
            </ul>
        </div>

        {{-- CONTENT HEADER --}}
        <div class="header-quick-nav">
            <div class="pull-left">
                <ul class="nav quick-section">
                    <li class="quicklinks">
                        <a href="#" class="" id="layout-condensed-toggle">
                            <div class="iconset top-menu-toggle-dark"></div>
                        </a>
                    </li>
                </ul>

                {{-- HEADER TITLE --}}
                <h4 class="quick-section">{{ config('app.name') }}</h4>
            </div>

            {{-- HEADER RIGHT SIDE SECTION --}}
            <div class="pull-right">
                <div class="chat-toggler">

                    {{-- SETTINGS CENTER --}}
                    @include('layouts.partials.settings')

                    {{-- PROFILE PICTURE --}}
                    <div class="profile-pic">
                        @if (Auth::user()->photo)
                            <img src="{{ asset( "img/profile/".Auth::user()->photo) }}" alt="" width="35" height="35"/>
                        @else
                            <img src="{{ asset('img/profile/default.png') }}" alt="" width="35" height="35">
                        @endif
                    </div>

                    <div class="cog">
                        @if (Auth::user()->hasRole('Admin'))
                            <a href="{{ route('config.painel') }}" title="Configurações">
                                <i class="fa fa-cog"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </div>{{-- END HEADER RIGHT SIDE SECTION --}}
        </div>{{-- END CONTENT HEADER --}}
    </div>{{-- END TOP NAVIGATION BAR --}}
</div>{{-- END HEADER --}}


<div class="page-container row-fluid">
    {{-- MENU --}}
    @include('layouts.partials.navigation')

    {{-- SCROLL UP --}}
    <a href="#" class="scrollup">Scroll</a>

    {{-- CONTENT --}}
    @yield('content')
</div>


{{-- JS TEMPLATE --}}
<script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.2.0.min.js" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrapv3/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-block-ui/jqueryblockui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/select2/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-mask/jquery.mask.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/boostrap-clockpicker/bootstrap-clockpicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
        type="text/javascript"></script>
<script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
{{-- JS PERSONAL --}}
<script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/knob/jquery.knob.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/noty/jquery.noty.packaged.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/cropper/cropper.min.js') }}" type="text/javascript"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1JhAcUiWd45YFiqJv1tQ_EhslrOzhfLM"></script>
<script src="{{ asset('webarch/js/webarch.js') }}" type="text/javascript"></script>
<script src="{{ asset('webarch/js/custom.js') }}" type="text/javascript"></script>

{{-- NOTIFICATIONS --}}
@if (session()->has('success'))
    <script>noty({text: "{!! session()->get('success') !!}", type: 'success'});</script>
@elseif (session()->has('error'))
    <script>noty({text: "{!! session()->get('error') !!}", type: 'error'});</script>
@endif

{{-- INLINE JAVASCRIPTS --}}
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
@yield('js')
</body>
</html>
