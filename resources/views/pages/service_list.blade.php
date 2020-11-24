@extends("layouts.app")
@section("content")
@section('head_title', __('Services | Beer baths in Prague') )
<header id="home" class="jumbotron bg-inverse text-center center-vertically">
<div class="container">
	<!--<img class="m-b-md" src="{{ URL::asset('assets/img/logo.png') }}" title="{{__('Beer baths in Prague')}}" alt="{{__('Beer baths in Prague')}}" /><br>-->
	<h1 class="display-3 m-b-md animated fadeInDown">{{__('Our services')}}</h1>
	<!--<h2 class="m-b-lg animated fadeInUp">blablablba</h2>-->
	<a class="btn btn-secondary-outline m-b-md animated fadeInUp slideble" href="#about" role="button">{{__('More')}}</a>
	<div class="list-inline social-share animated fadeInUp">
		<a title="{{__('Beer baths in Prague on Tripadvisor')}}" href="https://www.tripadvisor.com/Attraction_Review-g274707-d7377900-Reviews-Lazne_Pramen_Beer_and_Wine_spa-Prague_Bohemia.html" target="_blank"><img src="{{ URL::asset('assets/img/tripadvisor_white.svg') }}" alt="{{__('Beer baths in Prague')}}" /><br>
		<span>{{__('Tripadvisor header')}}</p></span></a>
	</div>
</div>
</header>

<section id="about" class="section_about">
	<div class="container">
		<div class="row p-y-lg">
            <div class="col-md-6 col-sm-12 col-xs-12">
				<div class="section_about_inside">
					<h3 class="animated fadeInUp">{{__('Homepage subheader')}}</h3>
					<p class="animated fadeInUp">{{__('Homepage description')}}</p>
					<p class="animated fadeInUp">{{__('Homepage description 2')}}</p>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<img src="{{ URL::asset('assets/img/section_about.jpg') }}" class="img-responsive" alt="" />
			</div>
		</div>
	</div>
</section>

<section id="about2" class="section_about">
	<div class="container">
		<div class="row p-y-lg">
            <div class="col-md-6 col-sm-12 col-xs-12">
				<img src="{{ URL::asset('assets/img/section_about2.jpg') }}" class="img-responsive" alt="" />
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="section_about_inside">
					<h3 class="animated fadeInUp">{{__('Homepage subheader')}}</h3>
					<p class="animated fadeInUp">{{__('Homepage description')}}</p>
					<p class="animated fadeInUp">{{__('Homepage description 2')}}</p>
				</div>
			</div>
		</div>
	</div>
</section>

{{--
<!--
<div class="wrapper-inner">
	<div class="container">
		@include("_particles.service")
     </div>
</div>
-->
--}}
@endsection