<?php 
/**
 * Quick n Dirty PHP MVC
 * 
 * User Controller
 * Used to manage users on the site, logins, registrations and credit purchases
 * 
 * @author Carl Saggs
 * @version 2011.03.10
 * 
 */
class User extends Controller {
	
	/**
	 * Make default action call register.
	 */
	public function index(){
	 	
	}
	/**
	 * Authenticate a user. Pretty just logs em in if the pw/username was right.
	 * @return unknown_type
	 */
	public function authenticate(){
		//Ensure neither username or password are empty
		if(!(empty($_POST['username']) || empty($_POST['password']))){
			//Get the user with that username.
			$user = $this->model->getUserByName($_POST['username']);
			//Check if password matchs whats in the db for the user (if user returned null(Doesnt exist) this will fail.
			if(Util::hash($_POST['password']) == $user['password']){
				//If they matched, create this user a login session.
				$this->session->createUserSession($user);
				//Send em back to the page they came from.
				$this->redirect();
			}
		}
		//If login failed, warn them via flash and redirect em back to where they came from.
		$this->setFlash("Incorrect username or password");
		$this->redirect();
	}
	
	public function logout(){
		$this->session->destroy();
		$this->redirect();
	}
	
	public function register(){
			
		if($this->requestMethod() !== 'POST'){
			$this->getView('regester');
		} else {
			if($this->validateRegister()){
				$this->create();
				$this->redirect('/');
			}
			$this->redirect();
		}
	}
	
	public function profile(){
		
		if(!$this->session->userSessionExist()) $this->redirect('/error');
		
		if($this->requestMethod() !== 'POST'){
			$this->getView('profile',$this->session->getUserData());
		} else {
			
			
		}
	}
	public function memberList(){
		
	}
	public function update(){
		
	}
	
	private function validateRegister(){
		if(isset($_POST['username']) && isset($_POST['email'])
	    && isset($_POST['password']) && isset($_POST['password2'])){
			
			//Check pw's match
			if($_POST['password'] !== $_POST['password2']){
				$this->setFlash("Passwords do not match.");
				return false;
			}
			//Check pw length
			if(strlen($_POST['password']) < 8 ){
				$this->setFlash("Passwords must be at least 8 charicters long");
				return false;
			}
			//Check username is alphanumeric and at least 3 charicters long
			if(strlen($_POST['username']) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/',$_POST['username'])){
				$this->setFlash("Username must be at least 3 charicters long.");
				return false;
			}
			//check email is valid
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
				$this->setFlash("Invalid email address.");
				return false;
			}
			//Is username free
			if($this->model->userNameExists($_POST['username'])) {
				$this->setFlash("This username is already in use.");
				return false;
			}
			
			return true;
			
		}
		
		$this->setFlash("Unknown error occured.");
		return false;
		
	}
	/**
	 * Create a new user account.
	 * 
	 */
	private function create(){
		
		$usr = $this->model->createUser($_POST['username'],
										$_POST['email'],
										$_POST['password']);
		if($usr != null){
			$this->session->createUserSession($usr);
		} 

	
	}
	

}