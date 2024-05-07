<?php
require_once 'core/init.php';

if($username = Input::get('user')){//if user isn't logged in 
	Redirect::to('index.php)';
}else{
	$user = new User;
	
	if(!$user->exists()){//user doesn't exist to 404 not found page 
		Redirect::to(404);
	}else{
		$data = $user->data();
?>	


	    <h3><?php echo escape($data->username); ?></h3> <?//getting user as H3  ?>
        <p>Name: <?php echo escape($data->name); ?></p> <?//getting name ?>
		
	<?php 
	}
}//end else 
	
?>