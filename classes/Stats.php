<?php
define('OWURL', 'https://overfast-api.tekrop.fr/players/'); //API URL
class Stats{//class to check stats 
	private $_db,
			$_accountStats = null; //storing api data in here
	

	public function __construct(){
		$this->_db = DB::getInstance();//creating database connection 
	}
	
	public function testConnection(){
		if($this->_db){
			echo "ok";
		} else {
			echo "Database connection failed.";
		}
	}
	
	public function apiStats($username){ //method to get api response and store data inside private variable
	
		$account = str_replace("#", "-", $username); //replacing # with - 
		$ch = curl_init(OWURL . $account); //putting url into $ch for easier reference 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //returns response as a string 
		$stats = curl_exec($ch);//executes the cURL session and stores response in $stats 
		
		if($stats === false){//Checks for cURL errors 
			echo "cURL error: " . curl_errno($ch). "\n";
			return [];//return an empty array as there are no stats to display
		}//end if 
		
		//Attempt to decode JSON response
		$accountStats = json_decode($stats, true);
		//return $accountStats; //returns data 
		
		//Check for JSON errors 
		if($accountStats === null && json_last_error() !== JSON_ERROR_NONE){
			echo "Error decoding JSON: " .json_last_error_msg() . "\n";//output raw JSON response for inestigation 
			echo "Raw JSON Response: " . $accountStats . "\n";
			return [];//REturn an empty array in case of an error 
		}//end if 
		
		curl_close($ch); //Close cURL session 
		
		$this->_accountStats = $accountStats;
	}
	
	
	public function displayData($username){//extract data 
		$this->apiStats($username);
		$data = $this->_accountStats;
		$username = $this->summaryData($data,'username'); //calling method with 'username'
		$avatar = $this->summaryData($data,'avatar'); //stores 'avatar' and other inside variable 
		$supportRank = $this->rankdata($data, 'support');
		$dpsRank = $this->rankdata($data, 'damage');
		$tankRank = $this->rankdata($data, 'tank');
		$hero1 = $this->hero($data, 0);
		$hero2 = $this->hero($data, 1);
		$hero3 = $this->hero($data, 2);
		echo $username . ' ' . $supportRank . ' ' . $dpsRank  . ' ' . $tankRank;
		echo '<br>';
		echo $hero1 . ' ' . $hero2 . ' ' . $hero3;
	}
	
	public function summaryData($data, $stats){ //extrats summary data 
		if(isset($data['summary'][$stats])) {//if $data is set 
			$statsValue  = $data['summary'][$stats]; //assign it to variable 
			return $statsValue;
		} else {
			return "$statsValue not found";
		}
	}
	
	public function rankData($data, $role){ //extrats rank data 
		// Initialize variables for competitive division rankings
		$tier = "";
		$rank = "Not Ranked";

		// Assign values if the data is available
		if (isset($data['summary']['competitive']['pc'][$role])) { //if data is set 
			$rankDivision = $data['summary']['competitive']['pc'][$role]['division']; //getting rank 
			$tier = $data['summary']['competitive']['pc'][$role]['tier'];
			$fullrank = $rankDivision. ' '.  $tier;
			return $fullrank;
		} else {
			return $rank; //returns "Not Ranked 
		}//end else 
	}//end function


	public function threeHeroes($data){//gets the top 3 played heroes from comp 
		$topHeroes = []; // Initialize an empty array to store the top 3 heroes

		if (isset($data['stats']['pc']['competitive']['heroes_comparisons']['time_played']['values'])) {
			$playedHeroes = $data['stats']['pc']['competitive']['heroes_comparisons']['time_played']['values'];

			// Iterate through the first 3 elements of the values array
			for ($i = 0; $i < min(3, count($playedHeroes)); $i++) {
				// Check if the 'hero' key exists in each value
				if (isset($playedHeroes[$i]['hero'])) {
					// Add the 'hero' value to the $topHeroes array
					$topHeroes[] = $playedHeroes[$i]['hero'];
				}
			}
		}

	
		return $topHeroes;
	}
	
	function hero($data, $number) {
		$topHeroes = []; // Initialize an empty array to store the top 3 heroes

		if (isset($data['stats']['pc']['competitive']['heroes_comparisons']['time_played']['values'])) {
			$playedHeroes = $data['stats']['pc']['competitive']['heroes_comparisons']['time_played']['values'];

			// Iterate through the first 3 elements of the values array
			for ($i = 0; $i < min(3, count($playedHeroes)); $i++) {
				// Check if the 'hero' key exists in each value
				if (isset($playedHeroes[$i]['hero'])) {
					// Add the 'hero' value to the $topHeroes array
					$topHeroes[] = $playedHeroes[$i]['hero'];
				}
			}
		}

		if (isset($topHeroes[$number])) {
			return $topHeroes[$number];
		} else {
			return null; // or handle the case where $number is out of range
		}
	}
	
	public function insertAccount($fields = array()){//insert account  
		
		if(!$this->_db->insert('account', $fields)){ //calling insert method from DB. If it'e empty throw error otherwise will enter in user 
			throw new Exception('There was a problem creating an account.');
		}//end if 
	}//end function 

}