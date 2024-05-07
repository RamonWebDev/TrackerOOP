<?
class User {
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');
		
		if(!$user){//checks if user is signed in
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)){
					$this->_isLoggedIn = true;
				} else {
					//process logout
				}//end else 
			}//end if 
		}else {
			$this->find($user);
		}//end else
		
	}//end function 
	
	public function update($fields = array(), $id = null){ //update method 
		
		if(!$id && $this->isLoggedIn()){ //if $id not null and user is logged in 
			$id = $this->data()->id; //assigns id to $id 
		}//end if
		
		if(!$this->_db->update('users', $id, $fields)){ //if not user will throw error 
			throw new Exception('There was a problem updating');
		}//end if 
	}//end function 
	
	
	
	public function create($fields = array()){//create user 
		
		if(!$this->_db->insert('users', $fields)){ //calling insert method from DB. If it'e empty throw error otherwise will enter in user 
			throw new Exception('There was a problem creating an account.');
		}//end if 
	}//end function 
	
	
	public function find($user = null) {//find user 
		
		if($user){ //if user is not null 
			$field = (is_numeric($user)) ? 'id' : 'username'; //$field will either be id else username 
			$data = $this->_db->get('users', array($field, '=', $user)); //using get method to get user 
			
			if($data->count()){//if there is a user found 
				$this->_data = $data->first();//gets first result 
				return true; //returns true if found 
			}//end if 		
		}//end if 
		return false;//returns false if not 
	}//end function 
	
	
	public function login($username = null, $password = null, $remember = false) {//log in method 
			
			if(!$username && !$password && $this->exists()){ //if username and password are empty and user exists  
				Session::put($this->_sessionName, $this->data()->id);//stores user's ID in a session variable allowing user to be authenticated across multiple requests
			} else {
				$user = $this->find($username); //setting $user to the username if it's found 
					
				if($user) {//if user is set 
					if(password_verify($password,$this->data()->password)){ //compares passwords 
						Session::put($this->_sessionName, $this->data()->id);//creates session to store user ID 
						if($remember) {//runs if remember me is checked
							$hash = Hash::unique(); //creates hash 
							$hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));//checks if user hash exists 
							
							if(!$hashCheck->count()){//if there is no hash it will add to users_session table 
								$this->_db->insert('users_session', array(//calling insert method and setting query 
								'user_id' => $this->data()->id,
								'hash' => $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;//if hash found retrieves 
							}//end else 
								
								Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));//gives cookie expiry 
								
						}//end if 
						return true;
					}//end if 
				}//end if
				
			}//end else
			
			return false;
		
	}//end function
	
	public function logout(){
		
		$this->_db->delete('users_session', array('user_id', '=', $this->data()->id));//deleting users_session 
		
		Session::delete($this->_sessionName);//deleting session 
		Cookie::delete($this->_cookieName);//deleting cookie 
	}//end function
	
	public function hasPremission($key) {//checking if user has premission 
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group)); //$group calls get method to know what group the user is in 
		if($group->count()){
			$permissions = json_decode($group->first()->permissions, true);
			
			if(isset($permissions[$key]) ? $permissions[$key] : 0) {//if premissions is set returns 1 else 0 
				return true;
			}//end if 
		}//end if
		return false;
	}//end function
	
	
	public function exists(){//method for checking if user exists 
		return (!empty($this->_data)) ? true : false; //if not empty return true else return false 
	}//end function
	
	public function data() {//returns data 
		return $this->_data;
	}//end function 
	
	
	public function isLoggedIn() {//returns if user is logged in 
		return $this->_isLoggedIn;
	}//end function
	
}//end class 