@yield('css')
<!-- Layout config Js -->
<script src="{{ config('kztek_config.url_public').('assets/js/layout.js') }}"></script>
<!-- Bootstrap Css -->
<link href="{{ config('kztek_config.url_public').('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ config('kztek_config.url_public').('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ config('kztek_config.url_public').('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ config('kztek_config.url_public').('assets/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
{{-- @yield('css') --}}
