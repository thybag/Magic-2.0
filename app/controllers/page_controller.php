<?php 
/**
 * Quick n Dirty PHP MVC
 * 
 * Page Controller
 * Just used for static pages around the site.
 * 
 * @author Carl Saggs
 * @version 2011.03.11
 * 
 */
class Page extends Controller {

	public $requireModel = false;
	
	function __construct($path){
		parent::__construct($path,false);
	}
	
	/**
	 * Make the default action call about page
	 */
	public function index(){
		$this->getView('index');
	}
	
	/**
	 * Display about page
	 */
	public function about(){

		$this->getView('about');
	}
	/**
	 * Display contact page
	 */
	public function contact(){

		$this->getView('contact');
	}
	/**
	 * Display feedback page
	 */
	public function feedback(){

		$this->getView('feedback');
	}
}