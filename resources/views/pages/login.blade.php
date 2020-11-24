@extends("layouts.app")

@section('head_title', 'Login | '.getcong('site_name') )
@section('head_url', Request::url())

@section("content")

<header id="home" class="jumbotron bg-inverse text-center center-vertically contacts_page">
<div class="container">
	<h1 class="display-3 m-b-lg animated fadeInDown">{{__('Log in')}}</h1>
	<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
				@if(Session::has('flash_message'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						{{ Session::get('flash_message') }}
					</div>
				@endif
				<div class="message">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
				<div>
					{!! Form::open(array('route' => 'login','class'=>'','id'=>'loginform')) !!}
						<div class="form-group">
							<label for="email">E-mail:</label>
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter your e-mail">
						</div>
						<div class="form-group">
							<label for="password">Password:</label>
							<input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
						</div>
						<div class="checkbox">
							<label for="checkbox1"><input type="checkbox" name="remember" id="checkbox1" /> Remember me</label>
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-gradient">Sign in</button>
						</div>
						<div class="form-group checkbox">                            
							<p>Don't have an account? <a href="{{ route('register') }}">Sign up</a><br/>  
							<a href="{{ route('paswordEmail') }}">Forgot password?</a></p>
						</div>
					{!! Form::close() !!} 
				</div>
			</div>
		</div>
</div>
</header>

@endsection