<?php 
session_start();

//Globals configuration array 
$GLOBALS['config'] = array (

	//MySQL database configuration
	'mysql' => array(
		'host' => 'localhost', //Database host address
		'username' => 'username',
		'password' => 'Password',
		'db' => 'Database Name'
	),
	
	//User Session remember settings 
	'remember' => array(
		'cookie_name' => 'hash', //Cookie name used to remember a user's session(e.g., hash)
		'cookie_expiry' => 60400 //Cookie expiry time in seconds(7 days)
	), 
	
	//users session managment settings 
	'session' => array(
		'session_name' => 'user', //session name under which user session data will be saved 
		'token_name' => 'token' //Name of the token used to prevent cross site request forgery(CSRF)
	)
	
);

spl_autoload_register(function($class) {//autoloading is a way to automatically include of require class files 
	require_once 'classes/' . $class . '.php'; //constructs the file path for the class based on the class name required_once is used to prevent issues
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){//if cookie exists and user is not logged in
	$hash = Cookie::get(Config::get('remember/cookie_name'));//retrives the value of the remember cookie which contains hash
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));//check if hash exists
	
	if($hashCheck->count()){//checks if found matching hash 
		$user = new User($hashCheck->first()-user_id);//creates new user object by providing ID found 
		$user->login();//user is logged in 
	}
}//end if