<?php 
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){//if user isn't loggeed in return to index.php
	Redirect::to('index.php');
}//end if 

if(Input::exists()){//checks if user exists 
	if(Token::check(Input::get('token'))){//gets token and then checks if that token exists 
		$validate = new Validate(); //creating new validate object 
		$validation = $validate->check($_POST, array( //calling check method to check for any errors 
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password_new' => array(
				'required' => true,
				'min' => 6
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_new'
			),
		));
		
        if($validation->passed()){
            if (!password_verify(Input::get('password_current'), $user->data()->password)) {
                echo 'Your password is incorrect';
            } else {
                $user->update(array(
                    'password' => Hash::make(Input::get('password_new'))
                ));
        
                Session::flash('home', 'Your password has been changed!');
                Redirect::to('index.php');
            }
            } else {
			foreach($validation ->errors() as $error) {
				echo $error, '<br>';
			}//end foreach 
		}//end else
	}//end if
}//end if 
?>

<form action="" method="post">

	<div class="field">
		<label for="password_current">Current Password</label>
		<input type="password" name="password_current" id="password_current">
	</div>
		
	<div class="field">
		<label for="password_new">New Password</label>
		<input type="password" name="password_new" id="password_new">
	</div>
		
	<div class="field">
		<label for="password_new_again">Repeat Password</label>
		<input type="password" name="password_new_again" id="password_new_again">
	</div>	
		
	<div class="field">
		<input type="submit" value="Change">
		<input type="hidden" name="token" value="<? echo Token::generate(); ?>">
	</div>


</form>