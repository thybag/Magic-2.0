<?php
/**
 * Quick n Dirty PHP MVC
 * 
 * A tiny little MVC framework devloped for this project
 * This page handles new requests to the framework, Parsing the url and includeing the required files.
 * 
 * @author Carl Saggs
 * @version 2011.03.10
 * 
 */

//Start session
session_start(); 

//Import Utility class's Util/Config/Session
require_once("magic/util.php");
//Load in base Class's Controller/Model
require_once("magic/basic.php");
//Get Config
require_once("app/config/main.php");
require_once("app/config/routes.php");

$router = new Router();
$router->load(); //parse path and begin system