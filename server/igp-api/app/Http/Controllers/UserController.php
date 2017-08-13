<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\User;
use JWTAuthException;
use Cookie;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user){
    	$this->user = $user;
    }

    public function register(Request $request){
    	$user = $this->user->create([
    		'name' => $request->get('name'),
    		'username' => $request->get('username'),
    		'email' => $request->get('email'),
    		'dob' => $request->get('dob'),
    		'password' => bcrypt($request->get('password')),
    		'phone_number' => $request->get('phone'),
    		'picture' => ' '
    		]);
    	return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){
    	$credentials = $request->only('username', 'password');
    	$token = null;
    	try{
    		if(!$token = JWTAuth::attempt($credentials)){
    			return response()->json(['invalid_username_or_password'], 422);
    		}
    	}catch(JWTAuthException $e){
    		return response()->json(['failed_to_create_token'], 500);
    	}
    	return response()->json(compact('token'));
    }

    public function getAuthUser(Request $request){
    	$user = JWTAuth::toUser($request->token);
    	return response()->json(['result' => $user]);
    }

    public function updateProfile(Request $request){
    	$user = JWTAuth::toUser($request->token);
    	$user2 = $this->user->find($user->id);
    	$user2->update($request->all());
    	return response()->json('User Updated Successfully');
    }

    public function logout(Request $request){
    	$status = JWTAuth::invalidate($request->token);
		return response()->json('ahaha');
    }
    public function generateCaptcha(){
		header('Content-Type: image/png');

		$im = imagecreatetruecolor(75, 30);

		$white = imagecolorallocate($im, 255, 255, 255);
		$grey = imagecolorallocate($im, 128, 128, 128);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 75, 30, $white);

		$text = str_random(4);
		session(['captcha'=>$text]);
		Cookie::make('captcha', $text, 60);
		$font = public_path('assets').'/font/Laborate.ttf';
		imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);
		imagettftext($im, 20, 0, 10, 20, $black, $font, $text);
		return '<img src="'.imagepng($im).'">';
    }

    public function validateCaptcha(Request $request){
    	return strcmp(session('captcha'),$request->captcha);
    }
}