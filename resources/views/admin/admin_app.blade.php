<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title> {{getcong('site_name')}} Администрирование</title>
<link href="{{ URL::asset('upload/'.getcong('site_favicon')) }}" rel="shortcut icon" type="image/x-icon" />
<link rel="stylesheet" href="{{ URL::asset('admin_assets/css/style.css') }}">
<script src="{{ URL::asset('admin_assets/js/jquery.js') }}"></script>
<script src="{{ URL::asset('assets/js/axios.min.js')}}"></script>
<?php if( env('APP_DEBUG') === false ): ?>
    <script src="{{ URL::asset('assets/js/vue.js') }}"></script>
<?php else: ?>
<script src="{{ URL::asset('assets/js/vue-debug.js') }}"></script>
<?php endif; ?>
</head>
<body class="sidebar-push sticky-footer">
@include("admin.topbar")
@include("admin.sidebar")
<div class="container-fluid">
    @yield("content")
    {{--
    <!--
    <div class="footer">
        <a href="{{ URL::to('admin/dashboard') }}" class="brand">
            {{getcong('site_name')}}
        </a>
    </div>
    -->
    --}}
</div>
<div class="overlay-disabled"></div>
<script src="{{ URL::asset('admin_assets/js/plugins.js') }}"></script>
</body>
</html>