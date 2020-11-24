@extends("admin.admin_app")

@section("content")
<div id="main">
	<div class="page-header">
		
		<div class="pull-right">
			<a href="{{ route('userAdd') }}" class="btn btn-primary">Добавить пользователя <i class="fa fa-plus"></i></a>
		</div>
		<h2>Пользователи</h2>
	</div>
	@if(Session::has('flash_message'))
				    <div class="alert alert-success">
				    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
				        {{ Session::get('flash_message') }}
				    </div>
	@endif
     
<div class="panel panel-default panel-shadow">
    <div class="panel-body">
         
        <table id="data-table" class="table table-striped table-hover dt-responsive" cellspacing="0" width="100%">
            <thead>
	            <tr>
	                <th>Тип</th>
	                <th>Фото</th>
	                <th>Имя</th>
	                <th>Email</th>
					<th>Телефон</th> 
	                <th class="text-center width-100">Действие</th>
	            </tr>
            </thead>

            <tbody>
            @foreach($allusers as $i => $users)
         	   <tr>
            	<td>{{ $users->usertype }}</td>
            	<td> @if($users->image_icon)
                                 
									<img src="{{ URL::asset('upload/members/'.$users->image_icon.'-s.jpg') }}" width="80" alt="person">
								@endif</td>
                <td>{{ $users->name }}</td>
                <td>{{ $users->email}}</td>
                <td>{{ $users->phone}}</td>
                <td class="text-center">
                
                <div class="btn-group">
	                <a class="btn btn-default" href="{{ route('userEdit', ['id' => $users->id]) }}"><i class="md md-edit"></i></a>
	                
	                
					<button type="button" class="btn btn-default-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="md md-delete"></i><span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right" role="menu"> 
						
						
						<li><a href="{{ route('userDelete', ['id' => $users->id]) }}"><i class="md md-delete"></i> Удалить</a></li>
					</ul>
				</div> 
                
            </td>
                
            </tr>
           @endforeach
             
            </tbody>
        </table>
    </div>
    <div class="clearfix"></div>
</div>

</div>



@endsection