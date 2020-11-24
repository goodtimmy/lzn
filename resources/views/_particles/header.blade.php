<nav class="navbar navbar-dark bg-inverse bg-inverse-custom navbar-fixed-top">
    <div class="container">
        <a class="navbar-brand slideble" href="/"><img src="{{ URL::asset('assets/img/logo_white.svg') }}" class="img-responsive" alt="" /></a>
		<button class="navbar-toggler hidden-md-up pull-right burger-icon" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
        <div id="navbarSupportedContent" class="collapse navbar-toggleable-custom" role="tabpanel" aria-labelledby="navbarSupportedContent">
            <ul class="nav navbar-nav pull-right">				
				<!--
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('/')}}" href="{{ URL::to('/') }}">
						{{__('Home')}}
					</a>
				</li>
				-->
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('procedure')}}" href="{{ route('procedure') }}">
						{{__('Procedure')}}
					</a>
				</li>
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('reservation')}}" href="{{ route('reservation') }}">
						{{__('Reservation')}}
					</a>
				</li>
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('groups')}}" href="{{ route('groups') }}">
						{{__('For groups')}}
					</a>
				</li>
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('photo-video')}}" href="{{ route('photo-video') }}">
						{{__('Photo and video')}}
					</a>
				</li>
				<li class="nav-item nav-item-toggable">
					<a class="nav-link {{classActivePath('contacts')}}" href="{{ route('contacts') }}">
						{{__('Contacts')}}
					</a>
				</li>
				<li class="nav-item nav-item-toggable">
				<div class="lang_block">
					<button class="lang_button" type="button" data-toggle="collapse" data-target="#languages" aria-expanded="false" aria-controls="languages"><img src="{{ URL::asset('assets/img/'. Lang::locale() .'.svg') }}" class="lang_icon" alt="" /><span>{{Lang::locale()}}</span></button>
					<div id="languages" class="panel-collapse collapse" role="tabpanel">
					<ul class="langswitcher">
						<li><a @if(Config::get('app.locale') == 'cs')class="active"@endif href="{{@getLangURI('cs')}}"><img src="{{ URL::asset('assets/img/cs.svg') }}" class="lang_icon" alt="" />Čeština</a></li>
						<li><a @if(Config::get('app.locale') == 'en')class="active"@endif href="{{@getLangURI('en')}}"><img src="{{ URL::asset('assets/img/en.svg') }}" class="lang_icon" alt="" />English</a></li>
						<li><a @if(Config::get('app.locale') == 'de')class="active"@endif href="{{@getLangURI('de')}}"><img src="{{ URL::asset('assets/img/de.svg') }}" class="lang_icon" alt="" />Deutsch</a></li>
						<li><a @if(Config::get('app.locale') == 'ru')class="active"@endif href="{{@getLangURI('ru')}}"><img src="{{ URL::asset('assets/img/ru.svg') }}" class="lang_icon" alt="" />Русский</a></li>
					</ul>
					</div>
				</div>
				</li>
            </ul>
        </div>
    </div>
</nav>