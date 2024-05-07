<?php 
class Hash {
	public static function make($string){//makes hash 
		return password_hash($string, PASSWORD_DEFAULT);//password_default is used to securely hash a password or sensitive data
	}
	
	public static function salt($length){
		return bin2hex(random_bytes($length));//makes sure the length of the salt is long 
	}
	
	public static function unique(){//generates a unique value that can be used as a salt or for other purpose 
		return self::make(uniqid());
	}
}