@extends("layouts.app")

@section('head_title', getcong('privacy_policy_title').' | '.getcong('site_name') )
@section('head_url', Request::url())

@section("content")
<!-- begin:header -->
    <div id="header" class="heading" style="background-image: url({{ URL::asset('assets/img/img01.jpg') }});">
      <div class="container">
        <div class="row">
          <div class="col-md-10 col-md-offset-1 col-sm-12">
            <div class="page-title">
              <h2>{{getcong('privacy_policy_title')}}</p>
            </div>
            <ol class="breadcrumb">
              <li><a href="{{ URL::to('/') }}">Главная</a></li>
              <li class="active">{{getcong('privacy_policy_title')}}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- end:header -->
<!-- begin:content -->
    <div id="content">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="blog-container">
              <div class="blog-content" style="padding-top:0px;">
                  
               		<div class="blog-text" style="padding-top:0px;">
						{!!getcong('privacy_policy_description')!!}
					</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end:content -->
 
@endsection
