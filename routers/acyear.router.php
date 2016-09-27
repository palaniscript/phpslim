<?php

// Get all years
$app->get('/acyears', function () use ($app) {	
	
	$acYear = new models\AcYear();
	$acYears = $acYear->getAllYears();
	$app->contentType('application/json');
	echo json_encode($acYears);
});

//Create year
$app->post('/acyear', function () use ($app) {	
	$acyear = json_decode($app->request->getbody(),true);
	$year = new models\AcYear();
	echo $year->addYear($acyear);
});

// DELETE year
$app->delete('/acyear', function () use ($app) {	
	$acyear = json_decode($app->request->getbody(),true);
    $year = new models\AcYear();
	$years = $year->deleteYear($acyear['id']);
	$app->contentType('application/json');
	echo json_encode($years);
});

// Get year by id
$app->post('/getAcYear', function () use ($app) {	
	$acyear = json_decode($app->request->getbody(),true);
    $year = new models\AcYear();
	$years = $year->getYearById($acyear['id']);
	$app->contentType('application/json');
	echo json_encode($years);
});