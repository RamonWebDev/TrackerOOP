<?php 
class Cookie {
	public static function exists($name){//checks if cookie exists 
		return(isset($_COOKIE[$name])) ? true : false; //if it's set will be true else returns false 
	}
	
	public static function get($name){
		return $_COOKIE[$name]; //returns cookie 
	}
	
	public static function put($name, $value, $expiry){//give cookie an expiry 
		if(setcookie($name, $value, time() + $expiry, '/')){ //use setcookie function and feed it paramaters given 
			return true;//returns true 
		}
		return false;
	}
	
	public static function delete($name){//delete cookie 
		self::put($name, '', time() - 1);
	}
}