<?php

include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Market.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Portfolio.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Share.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Share_Webservice.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Tab.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Transaction.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/User.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/User_Portfolio.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/User_Share.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/User_Share_Line.php';
include __DIR__.'/src/PNicolas/PortfolioBundle/Entity/Webservice.php';


include __DIR__.'/src/PNicolas/PortfolioBundle/Controller/DashboardController.php';


try {
	
	$user = new User();
	$user->set(1, 'Nicolas SOTRON');
	
	$dashboard = new DashboardController();
	$dashboard->setUser($user);
	
	$dashboard->loadData();
	
	$templateVar = [];
	$templateVar['user'] = $dashboard->user;
	$templateVar['tabs'] = $dashboard->tabs;
	
} catch (Exception $ex) {
	echo $ex->getMessage();
}

include (__DIR__.'/template/index.html');

