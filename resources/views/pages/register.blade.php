@extends("layouts.app")

@section('head_title', 'Create a new account | '.getcong('site_name') )
@section('head_url', Request::url())

@section("content")
<div id="content" class="registration_page">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12">
				<h1>Registration</h1>
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
					{!! Form::open(array('url' => 'register','class'=>'','id'=>'registerform')) !!}
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" name="name" id="name" placeholder="Enter your name">
						</div>
						<div class="form-group">
							<label for="email">E-mail:</label>
							<input type="email" class="form-control" name="email" id="email" placeholder="Enter your e-mail">
						</div>
						<div class="row">
							<div class="form-group col-md-6 col-sm-6 col-xs-12">
								<label for="password">Password</label>
								<input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
							</div>
							<div class="form-group col-md-6 col-sm-6 col-xs-12">
								<label for="password_confirmation">Repeat password</label>
								<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Repeat your password">
							</div>
						</div>
						<div class="form-group">
							<label for="phone">Phone:</label>
							<input type="text" class="form-control" name="phone" id="phone" placeholder="Enter your phone">
						</div>
						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-gradient">Create account</button>
						</div>
						<div class="form-group checkbox">                            
							<p>Already have an account? <a href="{{ URL::to('login') }}">Log in</a></p>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection