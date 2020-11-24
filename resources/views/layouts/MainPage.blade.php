<!DOCTYPE html>
<html lang="{{ Lang::locale() }}">
<head>
<title>@yield('head_title', getcong('site_name'))</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="@yield('head_description', getcong('site_description'))" />
<meta property="keywords" content="@yield('head_keywords', getcong('site_keywords'))" />
<meta property="og:type" content="article"/>
<meta property="og:title" content="@yield('head_title',  getcong('site_name'))" />
<meta property="og:description" content="@yield('head_description', getcong('site_description'))" />
<link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('/apple-touch-icon-57x57.png') }}" />
<link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('/apple-touch-icon-60x60.png') }}" />
<link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('/apple-touch-icon-72x72.png') }}" />
<link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('/apple-touch-icon-76x76.png') }}" />
<link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('/apple-touch-icon-114x114.png') }}" />
<link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('/apple-touch-icon-120x120.png') }}" />
<link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('/apple-touch-icon-144x144.png') }}" />
<link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('/apple-touch-icon-152x152.png') }}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('/apple-touch-icon-180x180.png') }}" />
<link rel="icon" type="image/png" href="{{ URL::asset('/favicon-32x32.png') }}" sizes="32x32" />
<link rel="icon" type="image/png" href="{{ URL::asset('/android-chrome-192x192.png') }}" sizes="192x192" />
<link rel="icon" type="image/png" href="{{ URL::asset('/favicon-96x96.png') }}" sizes="96x96" />
<link rel="icon" type="image/png" href="{{ URL::asset('/favicon-16x16.png') }}" sizes="16x16" />
<link rel="icon" type="image/png" href="{{ URL::asset('/favicon.ico') }}" />
<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/animate.min.css') }}" rel="stylesheet">
</head>
<body>
@include("_particles.header")
@yield("content")
@include("_particles.footer")
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
<script>
$(window).on('load', function () {
		$(window).scroll();
	});
	
$(window).bind('scroll load', function () {
	if ($(this).scrollTop() > 1) {
		$('.navbar').addClass('sticky');
	} else {
		$('.navbar').removeClass('sticky');
	}
});


$('a.slideble').click(function(){
    $('html, body').animate({
        scrollTop: $( $(this).attr('href') ).offset().top
    }, 500);
    return false;
});


$(document).ready(function () {
	$('.navbar-toggler').on('click', function () {
		$('.burger-icon').toggleClass('open');
	});
});

$('.navbar-nav>li>a').on('click', function(){
    $('#navbarSupportedContent').collapse('hide');
	$('.burger-icon').removeClass('open');
});

$('.form-control').each(function () {
	floatedLabel($(this));
});

$('.form-control').on('input', function () {
	floatedLabel($(this));
});

function floatedLabel(input) {
	var $field = input.closest('.form-group');
	if (input.val()) {
		$field.addClass('input-not-empty');
	} else {
		$field.removeClass('input-not-empty');
	}
}
</script>
</body>
</html>