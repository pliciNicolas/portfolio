<?php
echo "Salut";

include __DIR__.'/src/PNicolas/PortfolioBundle/Controller/DashBoardController.php';

$dashboard = new DashBoardController;

try {
	$dashboard->loadData();
} catch (Exception $ex) {
	echo $ex->getMessage();
}