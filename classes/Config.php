<?php 
class Config{ //class for getting info from core/init.php
	public static function get($path = null){//setting $path to null 
		if($path){
		$config = $GLOBALS['config']; //user variable $config for $GLOBALS['config']
			$path = explode('/',$path); //splits whatever is in path into an array with each cut being made after a '/'
			
			foreach($path as $bit){ //loop through each part in the array
				if(isset($config[$bit])){//if there is something in $config[$bit] runs code in if
					$config = $config[$bit];//$setting $config to bit(database name, username, password etc)
				}//end if 
			}//end foreach
			
			return $config;
		}//end if 
	}//end function 
}//end class