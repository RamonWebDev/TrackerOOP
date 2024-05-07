<?php
class Session{ 
	public static function exists($name){//checks if name exists 
		return(isset($_SESSION[$name])) ? true : false;
	}
	
	public static function put($name, $value){//stores data in php session variable 
		return $_SESSION[$name] = $value;
	}
	
	public static function get($name){//gets name 
		return $_SESSION[$name];
	}
	
	public static function delete($name){
		if(self::exists($name)) {
			unset($_SESSION[$name]);
		}//end if
	}//end function
	
	public static function flash($name, $string = ''){
		if(self::exists($name)){//checks if a specified name exists
			$session = self::get($name);//stores message from session 
			self::delete($name);//once message is read it is removed
			return $session;//stores message in session 
		}else{
			self::put($name, $string);//stores $string as a flash message 
		}//end else 
		return '';
	}
}