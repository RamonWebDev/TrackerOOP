<?php 
class Validate{
	private $_passed = false,
			$_errors = array(),
			$_db = null;
			
			
	public function __construct(){//gets database connection 
		$this->_db = DB::getInstance();
	}//end function 
	
	public function check($source, $items = array()){ //$source is $_POST and $items array
		foreach($items as $item => $rules){//outer loop goes through items to be validated. 
			foreach($rules as $rule => $rule_value){//inner loop goes through validation rules for each item 
				$value = trim($source[$item]);
				$item = escape($item);
				
				if($rule === 'required' && empty($value)){//checks if rule has required and if it's empty
					$this->addError("{$item} is required");//adds error with message 
				}else if(!empty($value)){
					switch($rule){
						case 'min':
								if(strlen($value) < ($rule_value)){//checks to make sure field matches the min length 
								$this->addError("{$item} must be minium of {$rule_value} characters.");
								}
							break;
							case 'max':
								if(strlen($value) > ($rule_value)){//checks to make sure field matches the max length
									$this->addError("{$item} must be a maximum of {$rule_value} characters.");
								}
							break;
							case 'matches':
								if($value != $source[$rule_value]){//checks ot make sure items match 
									$this->addError("{$rule_value} must match {$item}");
								}
							break;
							case 'unique':
								$check = $this->_db->get($rule_value, array($item, '=', $value));//makes sure item is unique 
								if($check->count()){//checks if there is at least 1 item that matches 
									$this->addError("{$item} already exists.");
								}//end if 
							break;
					}//end switch 
				}//end else if 
			}//end foreach 
		}//end foreach 
		
		if(empty($this->_errors)){//if no errors sets _passed to true 
			$this->_passed = true;
		}//end if 
		return $this;
	}//end function 
	
	public function addError($error){ 
		$this->_errors[] = $error; //adds error message to array 
	}
	
	public function errors(){ //returns errors 
		return $this->_errors;
	}
	
	public function passed(){ //returns passed property 
		return $this->_passed;
	}
}//end class