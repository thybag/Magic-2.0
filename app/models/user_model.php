<?php 
/**
 * Quick n Dirty PHP MVC
 * 
 * User Model
 * Provides database functions for User, get, update, create etc.
 * 
 * @author Carl Saggs
 * @version 2011.03.10
 * 
 */
class User_model extends Model {
	
	protected $tableName = 'user';
	
	/**
	 * getUser
	 * Returns an array describing the current user.
	 * 
	 * @param $uid User Id
	 * @return Array|null
	 */
	function getUser($uid){
		//Attempt to query user details
		return $this->getRow($uid);
	}
	
	/**
	 * Credit User
	 * Adds purchased credits to a user account + updates audit log with action
	 * 
	 * @param $user_id User's ID
	 * @param $credits Amount of credits user has purchased.
	 * @return unknown_type
	 */
	function creditUser($user_id,$credits){
		//Ensure values are ints
		$cost = (int)$credits;
		$uid = (int)$user_id;
		//Get time and create hash of audit row
		$time = time();
		$dataHash = sha1($time.'Credit Purchase'.'Google Checkout'.$uid.'credits'.$cost);
		
		try{
			//Get the secret key.
			$current_key = $this->getCurrentKey();
			//Start transaction
			$this->db->beginTransaction();
			//Give user their credits
			$this->db->exec("UPDATE user SET credits=credits+{$cost} WHERE id = {$uid}");
			//update secret key
			$this->db->exec("UPDATE store_info SET log_key=sha1(log_key) WHERE id = 1");
			//Record transaction, + encripted hash of data useing the current log key (one we had before the one we just generated)
			$this->db->exec("INSERT INTO audit_log 
							 VALUES ('', '{$time}', 'Credit Purchase', 'Google Checkout','{$uid}', 'credits', '{$cost}', 
							 AES_ENCRYPT('$dataHash','$current_key'))");
			//Save it all in to the db
			$this->db->commit();
		}
		catch(PDOException $e)
	    {
	    	//Somthing went wrong? roll back changes and return false.
	    	$this->db->rollBack();
	    	echo $e->getMessage();
	    	return false;
	    }
	    //Return true if it all worked :)
	    return true;
	}
	
	/**
	 * Get Current key
	 * Returns the secret key used to encrypt audit logs
	 * 
	 * @return String $SecretKey
	 */
	private function getCurrentKey(){
		try{
			//Query the store table to get the secret key.
			$query = $this->db->prepare('SELECT log_key FROM store_info WHERE id = 1');
			$query->execute();
		}
		catch(PDOException $e)
	    {
	    	//cacth error
	    	echo $e->getMessage();
	    }
	    //Get result and return key portion as string.
	     $q = $query->fetch();
	     return $q[0];
		
	}
	
	/**
	 * Check if a given user name is free for use.
	 * 
	 * @param $name Username
	 * @return Boolean
	 */
	function userNameExists($name){
		//Count records in the DB that have username that matchs $name
		try{
			$query = $this->db->prepare('SELECT COUNT(*) FROM user WHERE username = :name LIMIT 1');
			$query->bindParam(':name', $name, PDO::PARAM_INT);
			$query->execute();
		}
		catch(PDOException $e)
	    {
	    	//catch error.
	    	echo $e->getMessage();
	    }
	    //Get the number.
	    $q = $query->fetch();
	    //if the amount of records found with the username is 0, return true. (if not false)
	    return ($q[0] != 0);

	}
	
	/**
	 * Get a User by Name
	 * Query the database to find a user by their username.
	 * 
	 * @param $name Username
	 * @return Array|null
	 */
	function getUserByName($name){
		//Query for user with matching username.
		try{
			$query = $this->db->prepare('SELECT * FROM user WHERE username = :name LIMIT 1');
			$query->bindParam(':name', $name, PDO::PARAM_INT);
			$query->execute();
		}
		catch(PDOException $e)
	    {
	    	//Catch error, return null
	    	echo $e->getMessage();
	    	return null;
	    }
	    //return records as array (will be null if none found)
		return $query->fetch();
		
	}
	
	/**
	 * Create a New user in the DB
	 * 
	 * @param $user Username
	 * @param $email User Email
	 * @param $password User Password (non hashed)
	 * @return int|false (ID or new user, or false if fails)
	 */
	function createUser($user,$email,$password,$usergroup = 1){
		
		//Check the username wanted is free, if not return false
		
		//Hash the password
		$password = Util::hash($password);
		//Insert new user record in to DB. Lotsa strings
		try{
			$time = time();
			$query = $this->db->prepare('INSERT INTO user (username, email, password, usergroup, date_registered) VALUES (:user, :email, :pass, :ug, :regdate)');
			$query->bindParam(':user', 	$user, 	PDO::PARAM_STR, 255);
			$query->bindParam(':email', $email, 	PDO::PARAM_STR, 255);
			$query->bindParam(':pass', 	$password, 	PDO::PARAM_STR, 255);
			$query->bindParam(':ug', 	$usergroup, PDO::PARAM_INT);
			$query->bindParam(':regdate', 	$time, 	PDO::PARAM_STR, 255);
			$query->execute();
			//Return the id of the last created user.
			return $this->getRow($this->db->lastInsertId());
		}
		catch(PDOException $e)
	    {
	    	//if something went wrong, return false.
	    	echo $e->getMessage();
	    	return null;
	    }
	    
		
	}
	
	
}