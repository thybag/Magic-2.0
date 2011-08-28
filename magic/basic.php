<?php 
/**
 * Magic 2.0
 * 
 * Basic Class's
 * Contains the base Controller and Model Class's
 * 
 * @author Carl Saggs <carl@userbag.co.uk>
 * @version 2011.08.28
 * @package Magic.Core
 * 
 */

error_reporting(E_ALL | E_STRICT);

/**
 * Controller
 * 
 * Object which other controllers inherit from. Also used to load and create inital Controllers and Models
 * via the load function.
 * 
 * @author carl
 *
 */
class Controller {
	
	//Holder for the $path array
	public $path;
	//Holder for the model assoaited with this controller.
	public $model;

	//layout to use when rendering views
	public $layout = 'default';
	//Holder for session object
	protected $session;
	
	/**
	 * Initalises new Controller.
	 * 
	 * @param $path Array containing controller,method and value
	 * @param Boolean $requireModel Should a model be loaded
	 */
	function __construct($path, $requireModel=true) {
		//Store the path array in the object
		$this->path = $path;
		//load the Session for the object
		$this->session = Util::getSession();
		//Load model assoaited with this Controller
		if($requireModel){
			$this->model = Util::loadModel($path['controller']);
			//If model is not found, display critical error.
			if($this->model == null){ $this->error("Could not find model for '".$path['controller']."'",3); }
		}
	}	
	
	public function requestMethod(){
		return $_SERVER['REQUEST_METHOD'];
	}
	
	public function isAjax(){
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	/**
	 * Loads and displays a view.
	 * 
	 * @param $view Name of view file.
	 * @param $data Data to be used in the view.
	 */
	public function getView($view, $data=null){

		//Work out path to view file.
		$view_path = 'app/views/'.$this->path['controller'].'/'.$view.'.php';
		
		$a = new View($view_path, $data, $this->session, $this);
		$content = $a->getContent();

		$a = new View('app/views/layout/'.$this->layout.'.php', $content, $this->session, $this);
		$a->outputContent();
	}
	public function loadView($view, $data=null){
		$view_path = 'app/views/'.$this->path['controller'].'/'.$view.'.php';
		$view = new View($view_path, $data, $this->session, $this);
		return $view->getContent();
	}
	
	
	/**
	 * Redirect. Redirects a user to another location on the website.
	 * 
	 * @param $url Location to send user to
	 * @param $absolute Wheather or not the path provided is relative or absolute.
	 */
	public function redirect($url ='#', $absolute = false){
		//If redirect location was not specified, try to send them back to their refer location
		if($url =='#'){
			//if refer is set
			if($_SERVER['HTTP_REFERER'] !='' AND $_SERVER['HTTP_REFERER'] != null){
				//redirect to refer location and die.
				header("location: ".$_SERVER['HTTP_REFERER']);
				die();//stop once header set
			}else { 
				//if not, redirect them back to the site root.
				$url = '/'; 
			}
		}
		//Assuming a refer redirect did not take place, redirect them to the desire location.
		//If absolute redirect directly, if not, assume its relative and parse the path first.
		if($absolute){
			header("location: ".$url);
		}else{
			$url = Util::parsePath($url);//work out relative path.
			header("location: ".$url);
		}
		//Stop running once header is output.
		die();
	}
	
	/**
	 * Set Flash
	 * Used to set a notification or warning to display on the next page the user sees.
	 * 
	 * @param String $text Text to display
	 */
	public function setFlash($text){
		$this->session->set('flash', $text);
	}
	/**
	 * Get Flash
	 * Get the message set in the flash if it exists. Delete flash once its reterived.
	 * 
	 * @return unknown_type
	 */
	public function getFlash(){
		//If a flash has been set
		if($this->session->check('flash')){
			//get flash text
			$flash = $this->session->get('flash');
			//delete the flash from session
			$this->session->delete('flash');
			//return output code.
			return "<div class='flash'>{$flash}</div>";
		}else{
			//return blank if no flash is set.
			return '';
		}
	}

	/**
	 * Error. Used when an error has occured. Shows error message in default template.
	 * 
	 * @param String $errorMessage Message explaining what went wrong.
	 * @param String $errorLevel (1 = permission error, 2 = standard error, 3 = critical error)
	 */
	public function error($errorMessage ='Unknown error occured', $errorLevel = 1){
		//Work out error text for error level.
		if($errorLevel==1) $errorText = 'Permissions incorrect.';
		if($errorLevel==2) $errorText = 'An error has occured.';
		if($errorLevel==3) $errorText = 'A critical error has occured.';
		//Quickly throw together basic formatting for output
		$content = "<div class='innerContainer'>
		<h1>{$errorText}</h1>
		<p>{$errorMessage}</p>
		<br/><br/>
		</div>";
		//Display on basic layout
		require_once('app/views/layout/'.$this->layout.'.php');
		//Stop scripts.
		die();
	}
}

class View {
	private $content;
	private $parent;
	private $session;
	//Create View Object
	public function __construct($viewUrl, $data, $session, $parent) {
		//store
		$this->parent = $parent;
		$this->session = $session;
		$this->data = $data;
		//Make $base avaible
		$base = Config::get('base_dir');
		ob_start();
		//load view
		require_once($viewUrl);	
		//End buffer, store code to $content
		$this->content = ob_get_clean();
	}
	//Called by Controller
	public function getContent(){
		return $this->content;
	}
	public function outputContent(){
		echo $this->content;
	}
	//Called internally
	private function getFlash(){
		return $this->parent->getFlash();
	}
	private function addElement($element){
		$base = Config::get('base_dir');
		include "app/views/elements/{$element}.php";
	}
}
/**
 * Model
 * Used for models to inherit off. Doesnt do much other than store the instance of the
 * PDO object in $db.
 * 
 * @author carl
 *
 */
class Model {
	//Stores reference to PDO object.
	public $db;
	private $cache;
	protected $tableName;
	
	//Gets database and stores it in the $db holder.
	public function __construct() {
		$this->db = Util::getDB();
	}
	
	protected function getRow($id){
		//Attempt to query user details
		try{
			$query = $this->db->prepare("SELECT * FROM {$this->tableName} WHERE id = :id LIMIT 1");
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
		}
		catch(PDOException $e)
	    {
	    	//If it fails return null.
	    	echo $e->getMessage();
	    	return null;
	    }
	    //return records as array
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	
	protected function saveRow($values){
		try{
			$q = '';
			foreach ($values as $key => $newvalue){
				if($key !== 'id'){
					$q .= "{$key} = ':{$key}',";
				}
			}
			//remove trailing comma
			$q = substr_replace($newdata ,"",-1);
			
			//Create Query Object
			if(isset($values['id'])){
				//update
				$query = $this->db->prepare("UPDATE {$this->tableName} SET {$q} WHERE id =':id' LIMIT 1");
			}else{
				//create
				$query = $this->db->prepare("INSERT INTO {$this->tableName} VALUES {$q}");
			}
			//Assign Params
			foreach ($values as $key => $newvalue){
				$query->bindParam(':'.$key, $newvalue);
			}
			//Go Go Go!
			return $query->execute();
		}
		catch(PDOException $e)
	    {
	    	//If it fails return null.
	    	echo $e->getMessage();
	    	return null;
	    }
	}

}

class Router {
	
	private static $paths = array();
	//private $path;
	
	
	/**
	 * Add a new route to the router.
	 * 
	 * @param String $path
	 * @param Array $location
	 * @return void
	 */
	public static function add($path, $location){
		self::$paths[$path] = $location;
	}
	/**
	 * Static function to determine the route linked with a specific path.
	 * 
	 * @param String $path
	 * @return Array
	 */
	public static function getPath($path){
		if($path == null OR $path == '') return null;
		
		$pathParts = explode('/',$path);
		foreach($pathParts AS $unused){
			//Look for linkable, if not found drop down to next url bit and check again
			
			if(array_key_exists($path, self::$paths)){
				return self::$paths[$path];
			}
			array_pop($pathParts);
			$path = implode('/',$pathParts);
		}
		return null;
	}
	/**
	 * Reverse routing function
	 * 
	 * @param unknown_type $location
	 * @return unknown_type
	 */
	private static function reverseRoute($location){
		
		//check against router table
		foreach(self::$paths AS $path => $route){
			if($route['controller'] == $location['controller']){
				if($location['action']){
					if($route['method'] == $location['action']) return $path;
				}else{
					if($route['method'] == 'index') return $path;
				}
			}
		}

		return $location['controller'].'/'.$location['action'] ;
	
	}
	public static function url($location, $output = true){
		$path = Config::get('base_dir').'/'.self::reverseRoute($location);
		
		if($output){
			echo $path;
		}else{
			return $path;
		}
	}
	
	
	private function parsePath($url){
		
		//Grab the url string and make it useable
		$url = substr(str_ireplace(Config::get('base_dir'),'',$url),1);
		//see if we have a path set for this?
		$path = self::getPath($url);
		if($path != null){ return $path; }
		
		//if router entery is not set, work it out by other means.
		
		//break part url.
		$url_array = explode('/',$url);
		
		if(array_key_exists(0,$url_array) && $url_array[0] != ''){
			$path['controller'] 	= $url_array[0]; 
		}else{
			$path['controller'] 	= Config::get('default_controller');
		}
		
		if(!array_key_exists(1,$url_array)){
			$url_array[1] ='';
		}
		$path['method'] = $url_array[1]; 
		
		if(!array_key_exists(2,$url_array)){
			$url_array[2] ='';
		}
		$path['var'] = $url_array[2];
		
		$path['all'] = $url_array;
		
		return $path;
	}
	
	/**
	 * Load
	 * Used to create and setup controller needed to handle request.
	 * 
	 * @param String $controller
	 */
	public function load(){
		
		$url = $_SERVER['REQUEST_URI'];
		$path = $this->parsePath($url);
		
		
		$controller = $path['controller'];
		
		//get file location of requested controller.
		$control = "app/controllers/{$controller}_controller.php";
		//If controller file exists
		if(file_exists($control)){
			//include it
			require_once($control);
			//create a new instance of the controller
			$newController = new $controller($path);
			//Run main method
			
			if(method_exists($newController, $path['method'])){
				$newController->{$path['method']}();
			}else{
				$newController->index();
			}
		}else{ 
			//Display critical error if controller cannot be loaded.
			die("Cannot find controller '".$controller."'");
		}
	}



}