<?php
use App\Settings;
use App\User;
use App\Properties;
use App\Types;

 
if (! function_exists('getcong')) {

    function getcong($key)
    {
    	 
        $settings = Settings::findOrFail('1'); 

        return $settings->$key;
    }
}
 
if (!function_exists('classActivePath')) {
    function classActivePath($path)
    {
        $path = explode('.', $path);
        $segment = 2;
        if( Lang::locale() == App\Http\Middleware\LocaleMiddleware::$mainLanguage ) {
            $segment = 1;
        }
        foreach($path as $p) {
            if((request()->segment($segment) == $p) == false) {
                return '';
            }
            $segment++;
        }
        return ' active';
    }
}

if (!function_exists('classActivePathPublic')) {
    function classActivePathPublic($path)
    {
        $path = explode('.', $path);
        $segment = 1;
        
	    //Если URL (где нажали на переключение языка) содержал корректную метку языка
	    if (in_array(request()->segment(1), App\Http\Middleware\LocaleMiddleware::$languages)) {
	        $segment++;
	    } 
        
        if(request()->segment($segment) == $path[0]) {
            return ' active';
        }
        
        return '';
        
    }
}

if (!function_exists('classActlinkPathPublic')) {
    function classActlinkPathPublic($path)
    {
        $path = explode('.', $path);
        $segment = 1;
        
	    //Если URL (где нажали на переключение языка) содержал корректную метку языка
	    if (in_array(request()->segment(1), App\Http\Middleware\LocaleMiddleware::$languages)) {
	        $segment++;
	    } 
        
        if(request()->segment($segment) == $path[0]) {
            return ' act-link';
        }
        
        return '';
        
    }
}

if (! function_exists('getLangURI')) {
	function getLangURI($lang) 
	{ 
		$referer = Request::url(); //URL страницы
	    $parse_url = parse_url($referer, PHP_URL_PATH); //URI страницы
	
	    //разбиваем на массив по разделителю
	    $segments = explode('/', $parse_url);
	
	    //Если URL (где нажали на переключение языка) содержал корректную метку языка
	    if (in_array($segments[1], App\Http\Middleware\LocaleMiddleware::$languages)) {
	
	        unset($segments[1]); //удаляем метку
	    } 
	    
	    //Добавляем метку языка в URL (если выбран не язык по-умолчанию)
	    if ($lang != App\Http\Middleware\LocaleMiddleware::$mainLanguage){ 
	        array_splice($segments, 1, 0, $lang); 
	    }
	
	    //формируем полный URL
	    $url = Request::root().implode("/", $segments);
	    
	    //если были еще GET-параметры - добавляем их
	    if(parse_url($referer, PHP_URL_QUERY)){    
	        $url = $url.'?'. parse_url($referer, PHP_URL_QUERY);
	    }
	    return $url;
	}

    if (! function_exists('getInstagramFeed')) {
        function getInstagramFeed()
        {
            $feedFileName = 'instagram-feed.json';
            $feedFileFullPath = storage_path($feedFileName);

            $instagramPhotos = [];
            if (file_exists($feedFileFullPath)) {
                $instaResult = file_get_contents($feedFileFullPath);
                $insta = json_decode($instaResult);
                $instagramPhotos = $insta->graphql->user->edge_owner_to_timeline_media->edges;
            }

            $result = [];
            $i = 0;
            foreach ($instagramPhotos as $feed) {
                if(!$feed->node->is_video && $i < 6) {
                    $feed = $feed->node;
                    $feed->shortcode = "https://www.instagram.com/p/" . $feed->shortcode . "/";
                    $feed->text = $feed->edge_media_to_caption->edges[0]->node->text;
                    $result[]= $feed;
                    $i++;
                }
            }
            return $result;
        }
    }


}

