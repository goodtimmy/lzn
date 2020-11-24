@extends("admin.admin_app")

@section("content")

<div id="main">
	<div class="page-header">
		<h2>Краткий обзор</h2>
	</div>
    
 
<div class="row">
    
  	
    	
    	<a href="{{ route('articles') }}">
    	<div class="col-sm-6 col-md-3">
        <div class="panel panel-orange panel-shadow">
            <div class="media">
                <div class="media-left">
                    <div class="panel-body">
                        <div class="width-100">
                            <h5 class="margin-none" id="graphWeek-y">Статей</h5>

                            <h2 class="margin-none" id="graphWeek-a">
                                {{$articles}}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="media-body">
                    <div class="pull-right width-150">
                        <i class="fa fa-map-marker fa-4x" style="margin: 8px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </a>
    
    
    
    @if(Auth::user()->usertype=='Admin' OR Auth::user()->usertype=='SuperMaster')
    
    @else
    
    
    
    @endif
    
     
	
	 
</div>
 
</div>

@endsection