<!DOCTYPE html>
<html lang="en-US">
<head>
<title>{{__('404 error. Page not found.')}}</title>
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

<header id="home" class="jumbotron bg-inverse text-center center-vertically">
<div class="container">
	<h1 class="display-3 m-b-lg animated fadeInDown">{{__('404 error. Page not found.')}}</h1>
	<a class="btn btn-secondary-outline m-b-md animated fadeInDown" href="/" role="button">{{__('Back to the home page')}}</a>
</div>
</header>

@include("_particles.footer")
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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