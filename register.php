<?php 
require_once 'core/init.php';

if(Input::exists()){
    if(Token::check(Input::get('token'))){
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required'=> true,
                'min' => 2,
                'max' => 20,
                'unique'=>'users'//satys unique to users table
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
    ));//end validation
            
    if($validation->passed()){
        $user = new User();//creating new User object 
        $salt = Hash::salt(32); //creating salt with 32 length 

        try{
            $user->create(array(//calling create method and giving it this array. The create method uses the insert method 
                'username' => Input::get('username'),
                'password' => Hash::make(Input::get('password'), $salt),//password + hash
                'salt' => $salt,
                'name' => Input::get('name'),
                'joined' => date('Y-m-d H:i:s'),
                'group' => 1
            ));

            Session::flash('home', 'You have been registered and can now log in'); //if registered shows this message 
            Redirect::to('index.php');//redirect to index.php 
        } catch(Exception $e){
            die($e->getMessage());
        }//end catch
    }else{
        foreach($validation->errors() as $error){
            echo $error, '<br>'; //displays error 
        }//end fore ach
    }//end else
    
    }//end if
}//end if
?>

<form action="" method="post">
	<div class ="field">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
	</div>
	
	<div class ="field">
		<label for="password">Choose a Password</label>
		<input type="password" name="password" id="password">
	</div>
	
	<div class ="field">
		<label for="password_again">Enter Password Again</label>
		<input type="password" name="password_again" id="password_again">
	</div>
	
	<div class ="field">
		<label for="name">Enter Your Name</label>
		<input type="text" name="name" value="<? echo escape(Input::get('name')); ?>" id="name">
	</div>
	
	<input type="hidden" name="token" value="<?php echo Token::generate();?>">
	<input type="submit" value="Register">
	
</form>