<?php 
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');//stops special characters from being entered 
}

?>