@extends("layouts.app")
@section("content")
@section('head_title', __('Photo and video | Beer baths in Prague') )
<header id="home" class="jumbotron bg-inverse text-center center-vertically">
<div class="container">
	<!--<img class="m-b-lg" src="{{ URL::asset('assets/img/logo.png') }}" title="{{__('Beer baths in Prague')}}" alt="{{__('Beer baths in Prague')}}" /><br>-->
	<h1 class="display-3 m-b-lg animated fadeInDown">{{__('Photo and video')}}</h1>
	<!--<h2 class="m-b-lg animated fadeInUp">blablablba</h2>-->
	<a class="btn btn-secondary-outline m-b-md animated fadeInDown slideble" href="#about" role="button">{{__('Gallery')}}</a>
	<div class="list-inline social-share animated fadeInUp">
		<a href="https://www.tripadvisor.com/Attraction_Review-g274707-d7377900-Reviews-Lazne_Pramen_Beer_and_Wine_spa-Prague_Bohemia.html" target="_blank"><img src="{{ URL::asset('assets/img/tripadvisor_white.svg') }}" title="{{__('Beer baths in Prague')}}" alt="{{__('Beer baths in Prague')}}" /><br>
		<span>{{__('Tripadvisor header')}}</p></span></a>
	</div>
</div>
</header>


<section id="about" class="section_about">
	<div class="container">
		<div class="row">
			<div class="tz-gallery">
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/bridge.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/bridge.jpg') }}" alt=""></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/park.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/park.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/westcoast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/westcoast.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/traffic.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/traffic.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/coast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/coast.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/rails.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/rails.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/traffic.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/traffic.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/bridge.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/bridge.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/park.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/park.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/westcoast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/westcoast.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/reshetki.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/traffic.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/traffic.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/coast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/coast.jpg') }}" alt="" /></a>
				<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}" alt="" /></a>
			</div>
		</div>
	</div>
	
	<!--
	<div class="container gallery-container">
		<div class="tz-gallery gal">
			<div class="row">
				<div class="col-sm-12 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/bridge.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/bridge.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/park.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/park.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-12 col-md-8">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/traffic.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/traffic.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/coast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/coast.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/rails.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/rails.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-12 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/bridge.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/bridge.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/park.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/park.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/tunnel.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/coast.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/coast.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-12 col-md-8">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/traffic.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/traffic.jpg') }}" alt="" /></a>
				</div>
				<div class="col-sm-6 col-md-4">
					<a class="lightbox fas" href="{{ URL::asset('assets/img/gallery/rails.jpg') }}"><img src="{{ URL::asset('assets/img/gallery/rails.jpg') }}" alt="" /></a>
				</div>
			</div>
		</div>
	</div>
	-->
</section>
<script src="{{ URL::asset('assets/js/baguetteBox.min.js') }}"></script>
<script>
    baguetteBox.run('.tz-gallery');
</script>
@endsection