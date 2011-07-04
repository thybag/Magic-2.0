<?php 

//Paths
Config::set('base_dir',			'/Magic2'); //Pretty much nothing will work if this is wrong!
Config::set('http_path',		'http://localhost/magic2');
Config::set('file_path',		'/bla/bla/bla');

//Database
Config::set('db_host',				'localhost');	//DB_HOST
Config::set('db_name',				'magic2');		//DB_DATABASE
Config::set('db_user',				'root');		//DB_USERNAME
Config::set('db_pass',				'Password3');	//DB_PASSWORD

//General
Config::set('default_controller',	'page'); //Controller to use if none is specified
Config::set('salt',					sha1('myhash!'));//for hash