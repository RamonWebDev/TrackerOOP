<?
require_once 'core/init.php';

$user = new User();//create new user 

if(!$user->isLoggedIn()) { //redirects user if they aren't logged in back to index
	Redirect::to('index.php');
}//end if 

if(Input::exists()){  //checks if user exists
	if(Token::check(Input::get('token'))) {
		
		
		$validate = new Validate();//create new validate object 
		$validation = $validate->check($_POST, array( //checking rules 
			'name' => array(
				'required'=>true,
				'min' => 2,
				'max' => 50	
			)
		));//end array 
		
		if($validation->passed()) {//if validation doesn't have errors 
			
			try {
				$user->update(array( //calling update method in User class
					'name' => Input::get('name')//update name 
				));
				
				Session::flash('home', 'Your details have been changed.');
				Redirect::to('index.php');
				
			} catch(Exception $e) {
				die($e->getMessage());
			}//end catch 
			
		} else {
			foreach($validation->errors() as $error) {
				echo $error, '<br>';
			}//end foreach 
		}//end else 
		
	}//end if
}//end if 
?>

<form action="" method="post">

	<div class="field">
		<label for="name">Name</label>
		<input type="text" name="name" value="<? echo escape($user->data()->name);  ?>">
		<input type="submit" value="Update">
		<input type="hidden" name="token" value="<? echo Token::generate(); ?>">
	</div>


</form>