<?php
/**
 * Magic 2.0
 * A simple MVC framework aimed at rapidly creating user oriented websites.
 * 
 * This page handles new requests to the framework, Parsing the url and includeing the required files.
 * 
 * @author Carl Saggs <carl@userbag.co.uk>
 * @version 2011.08.28
 * @package Magic.Core
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