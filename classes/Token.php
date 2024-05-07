<?php
class Token {
	public static function generate(){//generates token 
		return Session::put(Config::get('session/token_name'), md5(uniqid()));
	}
	
	public static function check($token){//checks if token exists 
		$tokenName = Config::get('session/token_name'); //store method name in variable 
	
		if(Session::exists($tokenName) && $token === Session::get($tokenName)){ //checks if token matches the session token that's already been generated
			Session::delete($tokenName);//delete token so it can only be used once 
			return true; //returns true if token is valid
		}
		
		return false;
	}
}