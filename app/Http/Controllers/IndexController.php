<?php

namespace App\Http\Controllers;

use App\Bath;
use Auth;
use App;
use App\User;
use App\Settings;
use App\Service;
use App\ServiceTranslation;

use Mail;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
	 

    public function index()
    {  
    	if(!$this->alreadyInstalled()) {
           // return redirect('install');
        }
    	
    	
							   
        return view('pages.index');
    }
    
    public function subscribe(Request $request)
    {
    	
    	$data =  \Input::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    $rule=array(
		        'email' => 'required|email|max:75' 
		   		 );
	    
	   	 $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                echo '<p style="color: #db2424;font-size: 20px;">Поле email обязательно для заполнения.</p>';
                exit;
        } 
    	
    	$subscriber = new Subscriber;
    	 
    	$subscriber->email = $inputs['email'];
    	$subscriber->ip = $_SERVER['REMOTE_ADDR'];
		  
		 
	    $subscriber->save();
	    
	    echo '<p style="color: #189e26;font-size: 20px;">Successfully subscribe</p>';
        exit;
    	 
    }
	
	/**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }
	
	
	public function about_page()
    {   				
	    $services = Service::get();
	    
	    $locale = App::getLocale();
	    
	    forEach($services AS $service) {
		    $servicesTranslation = ServiceTranslation
		    	::where('service_id', '=', $service->id)
				->where('locale', '=', $locale)
				->first();
		    
		    if(!empty($servicesTranslation->id)) {
			    $service->name = $servicesTranslation->name;
			    $service->description = $servicesTranslation->description;
		    }
		}
		
		$settings = Settings::findOrFail('1');
		
		if($locale == 'ru') {
			$settings->about_title = $settings->about_title_ru;
			$settings->about_description = $settings->about_description_ru;
		}
	       
        return view('pages.about', compact('settings', 'services'));
    }
    
	public function terms_conditions_page()
    {   				   
        return view('pages.terms_and_conditions');
    }
    
    public function privacy_policy_page()
    {   				   	
        return view('pages.privacy');
    }
    
    
    public function groups_page()
    {
        $maxB1 = Bath::where('capacity', 1)->count();
        $maxB2 = Bath::where('capacity', 2)->count();

        return view('pages.groups', compact('maxB1', 'maxB2'));
    }

    public function photo_video_page()
    {
        return view('pages.photo_video');
    }

    public function successful_page()
    {
        return view('pages.payment_success');
    }
	
	public function contacts_page()
    {   				   	
        return view('pages.contacts');
    }

    public function procedure_page()
    {
        return view('pages.procedure');
    }
    
    public function testimonials()
    {   				   	
        return view('pages.testimonials');
    }
	
	public function reservation()
    {
        $maxB1 = Bath::where('capacity', 1)->count();
        $maxB2 = Bath::where('capacity', 2)->count();
        return view('pages.reservation', compact('maxB1', 'maxB2'));
    }
    
    public function contact_me_sendemail(Request $request)
    {   
    	
    	$data =  \Input::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    $rule=array(
		        'name' => 'required',
				'email' => 'required|email',
		        'message' => 'required' 
		   		 );
	    
	   	 $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return null;//redirect()->back()->withErrors($validator->messages());
        } 
        
        
        
        Mail::send('emails.contact',
        array(
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'phone' => $inputs['phone'],
            'message_text' => $inputs['message']
        ), function($message)
	    {
	        $message->from(getcong('site_email'));
	        $message->to(getcong('site_email'), getcong('site_name'))->subject(getcong('site_name').' Contact');
	    });
         
    	 

 		 return 'success';//redirect()->back()->with('flash_message', 'Thank you for your message!');
    }
    
    
    /**
     * Do user login
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function login()
    {   
    	if (Auth::check()) {
                        
            return redirect()->route('wallets'); 
        }
 
        return view('pages.login');
    } 
     
	 
    public function postLogin(Request $request)
    {
    	
    //echo bcrypt('123456');
    //exit;	
    	
      $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');

		 
		
         if (Auth::attempt($credentials, $request->has('remember'))) {

            if(Auth::user()->status=='0'){
                \Auth::logout();                 
                return redirect()->back()->withErrors('Ваш аккаунт еще не активирован, пожалуйста проверьте ваш почтовый ящик.');
            }

            return $this->handleUserWasAuthenticated($request);
        }

       // return array("errors" => 'The email or the password is invalid. Please try again.');
        //return redirect('/admin');
       return redirect()->back()->withErrors('Пара email и пароль введены неправильно. Попробуйте еще раз.');
        
    }
    
     /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request)
    {

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::user());
        }

        return redirect()->route('wallets'); 
    }
    
    public function register()
    {   
    	if (Auth::check()) {
                        
            return redirect()->route('home');
        }
 
        return view('pages.register');
    }
    
    public function postRegister(Request $request)
    { 
    	
    	$data =  \Input::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    $rule=array(
		        'name' => 'required',
		        'email' => 'required|email|max:75|unique:users',
		        'password' => 'required|min:3|confirmed' 
		   		 );
	    
	   	 $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
	      
	    $maxGroupId = User::max('group_id')+1;
	    
        $user = new User;
  
		//$user->salon_id = $salonID;
		$string = str_random(15); 
		$user_name= $inputs['name'];
		$user_email= $inputs['email'];
		
		$user->status = '0';
		$user->usertype = 'client';
		$user->name = $user_name;	 
		$user->email = $user_email;		 
		$user->password= bcrypt($inputs['password']); 
		$user->phone= $inputs['phone'];
		$user->group_id= $maxGroupId;
		 
		$user->confirmation_code= $string;
		 
	    $user->save();
	    
		Mail::send('emails.register_confirm',
        array(
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => $inputs['password'],
            'confirmation_code' => $string,
            'user_message' => 'test'
        ), function($message) use ($user_name,$user_email)
	    {
	        $message->from(getcong('site_email'),getcong('site_name'));
	        $message->to($user_email,$user_name)->subject('Registration Confirmation');
	    });	 	 
		
		 

            \Session::flash('flash_message', 'Пожалуйста подтвердите вашу регистрацию. Мы отправили вам на почтовый ящик ссылку для подтверждения.');

            return \Redirect::back();
 
         
    }
    
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }
    
    public function confirm($code)
    {   
    	 
        $user = User::where('confirmation_code',$code)->first();
 		
 		$user->status = '1'; 	
 		
 		$user->save();
 		
 		\Session::flash('flash_message', 'Подтверждение прошло успешно.');
 		
        return view('pages.login');
    }
    
}