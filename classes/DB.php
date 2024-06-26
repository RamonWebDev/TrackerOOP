<?php
class DB {
	private static $_instance = null;
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_results, 
			$_count = 0;
	
	private function __construct() {//sets up query 
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname='. Config::get('mysql/db'), Config::get('mysql/username'),Config::get('mysql/password'));	
		}catch(PDOException $e){
			die($e->getMessage());
		}//end catch 
	}//end construct
	
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}//end 
	
	public function query($sql, $params = array()) {
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}//end foreach
			}//end if
			
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
				}else {
					$this->_error = true;
				}//end else
		}//end if
	
		return $this;
	
	}//end function 
	
	public function action($action, $table, $where = array()) {
		if(count($where)===3) { //checks if field has 3 operators(ex 'username', '=', 'alex')
			$operators = array('=', '>', '<', '>=', '<=');
		
			$field = 	$where[0];
			$operator = $where[1];
			$value = 	$where[2];
			
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}//end if
			}//end if
		}//end if
		return false;
	}//end function
	
	public function get($table, $where){//gets query
		return $this->action('SELECT *', $table, $where); //returns SELECT * to be used in $action
	}// end function
	
	public function delete($table, $where){ //delete query 
		return $this->action('DELETE', $table, $where);
	}// end function
	
	public function results() {
		return $this->_results;
	}// end function
	
	public function first() {
		return $this->results()[0];
	}// end function
	
	public function update($table, $id, $fields) {
		$set = '';
		$x = 1;
		
		foreach($fields as $name => $value){
			$set .= "{$name} = ?"; //ex 'name' => Input::get('name') 'name' would be in $name. It was 'password' it would be password in {$name} 
			if($x < count($fields)){
				$set .= ', ';
			}//end if
			$x++;
		}//end foreach
		
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if(!$this->query($sql, $fields)->error()){
			return true;
		}//end if
		return false;
	}//end function
	
	public function insert($table, $fields = array()) {
			$keys = array_keys($fields); //holds info username, password, salt ect.
			$values = ''; //keeps track of "?"
			$x=1;
			
			foreach($fields as $field){
				$values .= '?';
				if ($x < count($fields)){
					$values .= ', ';
				}//end if 
				$x++;
			}//end foreach
			$sql = "INSERT INTO {$table}(`" . implode('`,`', $keys) . "`) VALUES ({$values})";
			
			if(!$this->query($sql, $fields)->error()){
				return true;
			}//end if
		return false;
	}// end function
	
	public function error() {//returns error 
		return $this->_error;
	}// end function
	
	public function count() {//returns result
		return $this->_count;
	}
	


}//end class



?>