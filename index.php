<?php 
require_once 'core/init.php';

if(Session::exists('home')){//if session exists class flash message 
	echo '<p>'. Session::flash('home') . '</p>';
}

$user = new User();//create new user 
if($user->isLoggedIn()){//shows info if user is logged in 

?>
					<?//gets username?>
	<p>Hello <a href="profile.php?user=<?echo escape($user->data()->username);?>"><?echo escape($user->data()->username);?></a>!</p>
	
		<ul>
			<li><a href="logout.php">Log out</a></li>
			<li><a href="changepassword.php">Change Password</a></li>
			<li><a href="update.php">Update</a></li>
		</ul>
		
	<?
	
		if($user->hasPremission('admin')){ //if user has admind 
			echo '<p>You are an admind </P>';
		}//end if 
		
	}else{
		echo '<p>You need to <a href="login.php">Log in</a> or <a href="register.php">register</a>';
	}
?>