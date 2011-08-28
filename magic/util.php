<?php 
/**
 * Magic 2.0
 * 
 * Utility Class's
 * This page includes a number of useful class's needed to support the appliction.
 * This includes: Util, Config and Session
 * 
 * @author Carl Saggs <carl@userbag.co.uk>
 * @version 2011.08.28
 * @package Magic.Core
 */


/**
 * Util
 * Provides a set of static functions to support the appliction being run.
 * 
 * @author Carl Saggs
 */

class Util {
	
	//Cache of loaded Model Objects
	private static $models;
	//Holder for the DB object
	private static $db 		= null;
	//Holder for the Session Object
	private static $session = null;
	
	/**
	 * loadModel
	 * includes a Model object of the spefied type (caches loaded Models so they are only imported once)
	 * @param String $model
	 * @return Model
	 */
    public static function loadModel($model) {
    	//If Model Object exists in cache, return Model.
    	if(isset($models[$model])) return $models[$model];
    	//Work out path to model file.
    	$model_path = "app/models/{$model}_model.php";
    	//If model exists
		if(file_exists($model_path)){
			//Load the model
			require_once($model_path);
			//create a new instance of the model
			$model_name = $model.'_Model';
			$new_model = new $model_name();
			//Add model to the cache
			self::$models[$model] = $new_model;
			//return this model
			return $new_model;
		}else{ 
			//If model was not found, return null.
			return null;
		}
    }
    /**
     * getDB
     * Returns PDO database object (stops duplicate db objects being created)
     * @return PDO Database
     */
    public static function getDB(){
    	//If the PDO DB object doesnt exist
    	if(self::$db == null){
    		//Load database details from config.
    		 $db_host = Config::get('db_host');
    		 $db_name = Config::get('db_name');
    		 $db_user = Config::get('db_user');
    		 $db_pass = Config::get('db_pass');
    		 //Attempt to create a new PDO Object
    		 try{
    		 	//Add PDO DB to holder
	    		 self::$db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass );
	    		 self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		 }
    		 catch(PDOException $e)
		    {	
		    	//Display error message if database connection and therefor (PDO object creation) was unsuccessful.
				echo "<h1>Unable to connect to the database.</h1>";
				echo '<p>Please ensure database settings are correct.</p>';
		    	echo '<p>'.$e->getMessage().'</p>';die();
		    }
    	}
    	//Return the PDO DB object in our holder.
    	return self::$db;
    }
    /**
     * getSession
     * returns the current session object
     * 
     * @return Session
     */
    public static function getSession(){
    	if(self::$session == null){
    		self::$session = new Session();
    	}
    	return self::$session;
    }
    /**
     * hash
     * Sha1's a provided password useing salt value set in config.
     * @param $text
     * @return String
     */
    public static function hash($text){
    	return sha1($text.Config::get('salt'));
    }
    /**
     * parsePath
     * Takes a path consisting of /Controller/Method/Value and translates it in to a useable url
     * Will probably work with route if one is added.
     * 
     * @param String $path
     * @param Boolean $showfullpath
     * @return String
     */
    public static function parsePath($path,$showfullpath = false){
    	
    	//If path doesnt have a precceding / add it
    	if(substr($path, 0,1) !== '/'){$path = '/'.$path;}
    	//If the want a full path attach the http path to the url, else just use the relative path.
    	if($showfullpath){
    		return Config::get('http_path').$path;
    	}else{
    		return Config::get('base_dir').$path;
    	}
    }
}
/**
 * Config
 * Very simple class that simply allows for values to be accessed
 * 
 * @author carl
 *
 */
class Config {
	//value holds an array that is used to store the data.
	private static $values;
	//Returns a stored item
	public static function get($item){
		return self::$values[$item];	
	}
	//Stores a new item (or updates an existing one)
	public static function set($item,$value){
		self::$values[$item] = $value;
	}
}

/**
 * Session
 * 
 * Provides basic functions to access and set data stored in the current session as well
 * as a number of advanced functions used to ensure session has not been tampered with. 
 * (since on raptor this is easy to do)
 * 
 * @author carl
 *
 */
class Session {
	//Booleon defining whether or not this session is validated
	private $validated = false;
	
	/**
	 * Check to see if an item is set within the session
	 * @param $item
	 * @return Boolean
	 */
	public function check($item){
		//does var exist
		return isset($_SESSION[$item]);	
	}
	/**
	 * Return an item from the session
	 * 
	 * @param $item
	 * @return varible 
	 */
	public function get($item){
		return $_SESSION[$item];
	}
	/**
	 * Set an item in the session
	 * 
	 * @param $item key to set the data to.
	 * @param $value data to be set
	 * 
	 */
	public function set($item,$value){
		//set var
		$_SESSION[$item] = $value;	
	}
	/**
	 * Delete an item from the session
	 * 
	 * @param $item
	 * @return true if successful
	 */
	public function delete($item){
		unset($_SESSION[$item]);	
		return true;
	}
	/**
	 * Destroy the current session
	 */
	public function destroy(){
		//set var
		session_destroy();	
	}

	 // User Session Functions
	/**
	 * Creates a new user Session
	 * 
	 * @param $uid 		User Id of new user
	 * @param $passHash Users password hash
	 */
	public function createUserSession($user){
		$this->set('usession.uid',			$user['id']	);
		$this->set('usession.info',			$user);
	}
	public function userSessionExist(){
		return ($this->check('usession.uid'));
	}
	public function getCurrent(){
		if($this->check('usession.uid')){
			return $this->get('usession.uid');
		}else{
			return 0;
		}
	}
	public function getUserData(){
		if($this->check('usession.uid')){
			return $this->get('usession.info');
		}else{
			return null;
		}
	}
	public function getUserInfo($idx){
		if(isset($_SESSION['usession.info'][$idx])){
			return $_SESSION['usession.info'][$idx];
		}else{
			return null;
		}
	}
}