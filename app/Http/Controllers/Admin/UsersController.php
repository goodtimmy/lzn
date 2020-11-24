<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Place;
use App\PlaceUser;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Session;
use Intervention\Image\Facades\Image; 
use Illuminate\Support\Facades\DB;

class UsersController extends MainAdminController
{
	
    public function userslist()    { 
         
        if(Auth::User()->usertype=="Admin") {
        
        	$allusers = User::orderBy('id')->get();
        
        } else {
	    
	    	$allusers = User::where('group_id', '=', Auth::User()->group_id)->orderBy('id')->get();
	        
        }
         
        return view('admin.pages.users',compact('allusers'));
    } 
     
    public function addeditUser()    { 
	       
        return view('admin.pages.addeditUser');
    }
    
    public function addnew(Request $request)
    { 
    	
    	$data =  \Input::except(array('_token')) ;
	    
	    $inputs = $request->all();
	    
	    $rule=array(
		        'name' => 'required',
		        'email' => 'required|email|max:75|unique:users,id',
		        'password' => 'min:6|max:15',
		        'image_icon' => 'mimes:jpg,jpeg,gif,png' 
		   		 );
	    
	   	 $validator = \Validator::make($data,$rule);
 
        if ($validator->fails())
        {
                return redirect()->back()->withErrors($validator->messages());
        } 
	      
		if(!empty($inputs['id'])){
           
            $user = User::findOrFail($inputs['id']);

        }else{

            $user = new User;

        }
		
		 
		//User image
		$user_image = $request->file('image_icon');
		 
        if($user_image){
            
            \File::delete(public_path() .'/upload/members/'.$user->image_icon.'-b.jpg');
		    \File::delete(public_path() .'/upload/members/'.$user->image_icon.'-s.jpg');
            
            $tmpFilePath = 'upload/members/';

            $hardPath =  str_slug($inputs['name'], '-').'-'.md5(time());
			
            $img = Image::make($user_image);

            $img->fit(376, 250)->save($tmpFilePath.$hardPath.'-b.jpg');
            $img->fit(80, 80)->save($tmpFilePath.$hardPath. '-s.jpg');

            $user->image_icon = $hardPath;
             
        }
		 
		$user->usertype = $inputs['usertype'];
		$user->name = $inputs['name'];		 
		$user->email = $inputs['email'];
		$user->phone = $inputs['phone'];
		$user->about = $inputs['about'];
		$user->facebook = $inputs['facebook'];
		$user->insta = $inputs['insta'];
		$user->group_id = Auth::User()->group_id;
		
		if($inputs['password'])
		{
			$user->password= bcrypt($inputs['password']); 
		}
		 
	    $user->save();
	    
	    /*$placeUser = PlaceUser::where('group_id', '=', Auth::User()->group_id);
	    
	    //remove all rules
	    $placeUser->where('user_id', '=', $user->id)->delete();
	    
	    //add rules depend on user type
	    if($inputs['usertype'] == 'Master') {
		    
		    $placeList = $inputs['places'];
		    
	    } elseif($inputs['usertype'] == 'SuperMaster') {
		    
		    $placeList = Place::select('id')->where('group_id', '=', Auth::User()->group_id)->get();
		    
		    forEach($placeList AS $item) $items[] = $item->id;
		    
			$placeList = $items;
	    }
	    
	    //print_r($placeUser);
	    //adding places 
	    forEach($placeList AS $place) {
		    $placeArray[] = ['user_id' => $user->id, 'place_id' => $place, 'group_id' => Auth::User()->group_id];
		}
	    $placeUser->insert($placeArray);
	    */
		
		if(!empty($inputs['id'])){

            \Session::flash('flash_message', 'Changes Saved');

        }else{
	        
            \Session::flash('flash_message', 'Added');
            
        }		     
        
        return redirect()->route('users');
         
    }     
    
    public function editUser($id)    
    {     
		$user = User::findOrFail($id);
		
		
		return view('admin.pages.addeditUser',compact('user'));
        
    }	 
    
    public function delete($id)
    {
    	$user = User::findOrFail($id);
        
		\File::delete(public_path() .'/upload/members/'.$user->image_icon.'-b.jpg');
		\File::delete(public_path() .'/upload/members/'.$user->image_icon.'-s.jpg');
			
		$user->delete();
		
        \Session::flash('flash_message', 'Deleted');

        return redirect()->back();

    }
    
     
   
    	
}
