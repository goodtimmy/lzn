@extends("layouts.MainPage")
@section("content")
@section('head_title', __('Beer baths in Prague') )
<header id="home" class="jumbotron bg-inverse text-center center-vertically">
<div class="container">
	<!--<img class="m-b-md" src="{{ URL::asset('assets/img/logo.png') }}" title="{{__('Beer baths in Prague')}}" alt="{{__('Beer baths in Prague')}}" /><br>-->
	<h1 class="display-3 m-b-md animated fadeInDown">{{__('Beer baths')}}<br>{{__('in Prague')}}</h1>
	<!--<h2 class="m-b-lg animated fadeInUp">blablablba</h2>-->
	<a class="btn btn-secondary-outline m-b-md animated fadeInUp slideble" href="{{ route('reservation') }}" role="button">{{__('Lázně Pramen Letná')}}<br><span>{{__('For groups')}}</span></a>
	<a class="btn btn-secondary-outline m-b-md animated fadeInDown slideble" href="https://www.pivnispa.cz/" target="_blank">{{__('Lázně Pramen Dejvická')}}<br><span>{{__('For individuals')}}</span></a>
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
					<a class="btn btn-secondary-outline m-b-md animated fadeInUp" href="{{ route('services') }}">{{__('Our services')}}</a>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<img src="{{ URL::asset('assets/img/section_about.jpg') }}" class="img-responsive" alt="" />
			</div>
		</div>
	</div>
</section>

<section id="design" class="section_design">
	<div class="container text-center">
		<h3 class="m-b-lg animated fadeInUp">{{__('Section design header')}}</h3>
		<a class="btn btn-secondary-outline m-b-md animated fadeInUp" href="{{ route('reservation') }}">{{__('Reservation')}}<br><span>{{__('Section design description')}}</span></a>
	</div>
</section>
@endsection