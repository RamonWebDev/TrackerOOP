<?php 
class Input{
	public static function exists($type = 'post'){
		switch($type){
			case 'post':
				return(!empty($_POST))? true : false;//if not empty return true else return false
			break;
			case 'get':
				return(!empty($_GET)) ? true : false;//if not empty return true else return false
			break;
			default: //if none match any of the case return false 
				return false;
			break;
		}//end switch
	}//end function 
	
	public static function get($item){//used to get an input item from $_GET or $_POST arrays 
		if(isset($_POST[$item])){ //checks if item exists in $_POST 
			return $_POST[$item];
		}else if(isset($_GET[$item])){//checks if item exists in $_GET 
			return $_GET[$item];
		}//end else 
		return '';//if item doesn't exists returns empty 
	}//end function 
}//end class