@extends("layouts.payment")
@section("content")

	<h1 class="display-1 m-b-lg animated fadeInDown">{{__('Your transaction is complete')}}</h1>
	<p class="m-b-lg animated fadeInDown">{{__('Thanks for your order')}}</p>
	<a class="btn btn-secondary-outline m-b-md animated fadeInDown" href="/" role="button">{{__('Back to the home page')}}</a>

@endsection

