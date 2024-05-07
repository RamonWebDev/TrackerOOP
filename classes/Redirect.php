<?php 
class Redirect{
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){//if location is just numbers 
				switch($location){//send user to 404 error page 
					case 404:
						header('HTTP/1.0 404 Not Found');
						include 'include/errors/404.php';
						exit();
					break;
				}//end switch
			}//end if 
			header('Location: ' . $location);
			exit();
		}//end if 
	}//end function 
}//end class