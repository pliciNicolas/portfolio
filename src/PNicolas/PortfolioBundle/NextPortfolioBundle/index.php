<?php

include __DIR__.'/Entity/Market.php';
include __DIR__.'/Entity/Portfolio.php';
include __DIR__.'/Entity/Share.php';
include __DIR__.'/Entity/Share_Webservice.php';
include __DIR__.'/Entity/Transaction.php';
include __DIR__.'/Entity/User.php';
include __DIR__.'/Entity/User_Share.php';
include __DIR__.'/Entity/User_Share_Line.php';
include __DIR__.'/Entity/User_Webservice.php';
include __DIR__.'/Entity/Webservice.php';


include __DIR__.'/Controller/Dashboard.php';


try {
	
	$user = new User();
	$user->set(1, 'Nicolas SOTRON');
	
	$dashboard = new Dashboard();
	$dashboard->setUser($user);
	
	$dashboard->loadData();

	
	$templateVar = [];
	$templateVar['user'] = $user;
	
} catch (Exception $ex) {
	echo $ex->getMessage();
}

include (__DIR__.'/template/index.html');

