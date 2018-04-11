<?php
include __DIR__.'/src/PNicolas/PortfolioBundle/Controller/DashBoardController.php';

$dashboard = new DashBoardController;

try {
	$dashboard->generate();
} catch (Exception $ex) {
	echo $ex->getMessage();
}