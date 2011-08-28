<?php 
/**
 * Magic 2.0
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
			$query = $this->db->prepare('INSERT INTO user (username, email, password, usergroup, date_registered, ip) VALUES (:user, :email, :pass, :ug, :regdate, :ip)');
			$query->bindParam(':user', 	$user, 	PDO::PARAM_STR, 255);
			$query->bindParam(':email', $email, 	PDO::PARAM_STR, 255);
			$query->bindParam(':pass', 	$password, 	PDO::PARAM_STR, 255);
			$query->bindParam(':ug', 	$usergroup, PDO::PARAM_INT);
			$query->bindParam(':regdate', 	$time, 	PDO::PARAM_STR, 255);
			$query->bindParam(':ip', 	$_SERVER['REMOTE_ADDR'], 	PDO::PARAM_STR, 255);
			 
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