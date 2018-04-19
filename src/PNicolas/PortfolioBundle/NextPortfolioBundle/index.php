<?php

include __DIR__.'/Entity/Market.php';
include __DIR__.'/Entity/Portfolio.php';
include __DIR__.'/Entity/Share.php';
include __DIR__.'/Entity/Share_Webservice.php';
include __DIR__.'/Entity/Transaction.php';
include __DIR__.'/Entity/User.php';
include __DIR__.'/Entity/User_Share.php';
include __DIR__.'/Entity/User_Share_Detail.php';
include __DIR__.'/Entity/User_Shares.php';
include __DIR__.'/Entity/User_Webservice.php';
include __DIR__.'/Entity/Webservice.php';


try {
	$user = new User();
	$user->loadData();
} catch (Exception $ex) {
	echo $ex->getMessage();
}

include (__DIR__.'/template/index.html');

