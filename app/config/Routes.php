<?php 

Router::add('batman/test/cake', array('controller' => 'user','method' => 'register'));
Router::add('batman/test', array('controller' => 'user','method' => 'login'));
Router::add('batman/user', array('controller' => 'user','method' => 'other'));
