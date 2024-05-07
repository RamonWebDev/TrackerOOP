<?php 
require_once 'core/init.php';

$user = new User();//create new user 
$user->logout();//call logout method 

Redirect::to('index.php');//redirect user to Index.php