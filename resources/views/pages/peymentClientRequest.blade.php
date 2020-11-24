@extends("layouts.payment")
@section("content")

	<div class="animated fadeInDown">

		<h1>{{ __('Payment request') }}</h1>

		{!! Form::open(['route' => 'payClientRequestProcess']) !!}

		<p>{{__('Name')}}: <b>{{$name}}</b></p>
		<p>{{__('Phone')}}: <b>{{$phone}}</b></p>
		<p>{{__('E-mail')}}: <b>{{$email}}</b></p>
		<p>{{__('Single bath')}}: <b>{{$baths_for_1}}</b></p>
		<p>{{__('Double bath')}}: <b>{{$baths_for_2}}</b></p>
		@if(isset($message))
			<p>{{__('Comment')}}: <b>{{$message}}</b></p>
		@endif
		<p>{{__('Price')}}: <b>{{$price}}</b></p>

		<input type="hidden" name="id_hash" value="{{$idHash}}" />

		<div>
			<button type="submit">{{__('Pay')}}</button>
		</div>

		{!! Form::close() !!}
	</div>

@endsection