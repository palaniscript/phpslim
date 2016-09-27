<?php

// Get all feeTypes
$app->get('/feetypes', function () use ($app) {	
	
	$feeType = new models\FeeType();
	$feeTypes = $feeType->getAllTypes();
	$app->contentType('application/json');
	echo json_encode($feeTypes);
});

//Create feeType
$app->post('/feetype', function () use ($app) {	
	$type = json_decode($app->request->getbody(),true);
	$feeType = new models\FeeType();
	echo $feeType->addType($type);
});

// DELETE feeType
$app->delete('/feetype', function () use ($app) {	
	$type = json_decode($app->request->getbody(),true);
    $feeType = new models\FeeType();
	$feeTypes = $feeType->deleteType($type['id']);
	$app->contentType('application/json');
	echo json_encode($feeTypes);
});

// Get feeType by id
$app->post('/getFeeType', function () use ($app) {	
	$type = json_decode($app->request->getbody(),true);
    $feeType = new models\FeeType();
	$feeTypes = $feeType->getTypeById($type['id']);
	$app->contentType('application/json');
	echo json_encode($feeTypes);
});