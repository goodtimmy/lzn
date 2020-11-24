@extends("admin.admin_app")

@section("content")

<div id="main">
	<div class="page-header">
		<h2> {{ isset($user->name) ? 'Редактировать: '. $user->name : 'Добавить пользователя' }}</h2>
		
		<a href="{{ URL::to('admin/users') }}" class="btn btn-default-light btn-xs"><i class="md md-backspace"></i> Назад</a>
	  
	</div>
	@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
	@endif
	 @if(Session::has('flash_message'))
				    <div class="alert alert-success">
				    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
				        {{ Session::get('flash_message') }}
				    </div>
	@endif
   
   	<div class="panel panel-default">
            <div class="panel-body">
                {!! Form::open(array('url' => array( route('POSTUserAdd') ),'class'=>'form-horizontal padding-15','name'=>'user_form','id'=>'user_form','role'=>'form','enctype' => 'multipart/form-data')) !!} 
                <input type="hidden" name="id" value="{{ isset($user->id) ? $user->id : null }}">
                  
                
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Имя</label>
                    <div class="col-sm-9">
                        <input type="text" name="name" value="{{ isset($user->name) ? $user->name : null }}" class="form-control">
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-3 control-label">Телефон</label>
                    <div class="col-sm-9">
                        <input type="text" name="phone" value="{{ isset($user->phone) ? $user->phone : null }}" class="form-control" value="">
                    </div>
                </div>
                
				<div class="form-group">
                    <label for="" class="col-sm-3 control-label">О пользователе</label>
                    <div class="col-sm-9">
                         
						<textarea name="about" cols="50" rows="5" class="form-control">{{ isset($user->about) ? $user->about : null }}</textarea>
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-3 control-label">Facebook</label>
                    <div class="col-sm-9">
                        <input type="text" name="facebook" value="{{ isset($user->facebook) ? $user->facebook : null }}" class="form-control" value="">
                    </div>
                </div>
				<div class="form-group">
                    <label for="" class="col-sm-3 control-label">Instagram</label>
                    <div class="col-sm-9">
                        <input type="text" name="insta" value="{{ isset($user->insta) ? $user->insta : null }}" class="form-control" value="">
                    </div>
                </div>
				<div class="form-group">
                    <label for="avatar" class="col-sm-3 control-label">Фото профайла</label>
                    <div class="col-sm-9">
                        <div class="media">
                            <div class="media-left">
                                @if(isset($user->image_icon))
                                 
									<img src="{{ URL::asset('upload/members/'.$user->image_icon.'-s.jpg') }}" width="80" alt="person">
								@endif
								                                
                            </div>
                            <div class="media-body media-middle">
                                <input type="file" name="image_icon" class="filestyle"> 
                            </div>
                        </div>
	
                    </div>
                </div>
				<div class="form-group" id="userType">
                    <label for="" class="col-sm-3 control-label">Тип пользователя</label>
                    <div class="col-sm-4"> 
                        <select name="usertype" id="basic" class="selectpicker show-tick form-control" data-live-search="true">
								@if(isset($user->usertype))
								
									<option value="User" @if($user->usertype=='User') selected @endif>User</option>
									<option value="Admin" @if($user->usertype=='Admin') selected @endif>Admin</option>
								 
								@else
								 
								    <option value="User">User</option>
									<option value="Admin">Admin</option>
								
								@endif
								 
						</select>
                    </div>
                </div>
                
				<hr />
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-9">
                        <input type="text" name="email" value="{{ isset($user->email) ? $user->email : null }}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">Пароль</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" value="" class="form-control">
                    </div>
                </div>
                 
                
                 
                <hr>
                <div class="form-group">
                    <div class="col-md-offset-3 col-sm-9 ">
                    	<button type="submit" class="btn btn-primary">{{ isset($user->name) ? 'Редактировать пользователя' : 'Добавить пользователя' }}</button>
                         
                    </div>
                </div>
                
                {!! Form::close() !!} 
            </div>
        </div>
   
    
</div>

<script type="text/javascript">
	$("#userType").change(function(){
		var placesList = $('#placesList');
		
		if( $(this).find('select').val() == 'Master' ) placesList.show(150);
		else placesList.hide(150);
	});
	
</script>

@endsection