<?php
include __DIR__.'/src/PNicolas/PortfolioBundle/Controller/DashBoardController.php';

$dashboard = new DashBoardController;

try {
	$dashboard->generate();
	$templates = [];
	$templates['user'] = array_shift($dashboard->users);
	$templates['dashboard'] = $dashboard->tabs;
	include (__DIR__.'/template/index.html');
	die();
} catch (Exception $ex) {
	echo $ex->getMessage();
}