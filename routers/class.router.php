<?php

// Get all classes and sections
$app->get('/classes', function () use ($app) {	
	
	$class = new models\Class();
	$classes = $class->getAllClasses();
	$app->contentType('application/json');
	echo json_encode($classes);
});

//Create a class
$app->post('/class', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
	$class = new models\Class();
	echo $feeType->addType($data);
});

// DELETE a class
$app->delete('/class', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Class();
	$classes = $class->deleteClass($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});

// Get class and section by id
$app->post('/getClass', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Class();
	$classes = $feeType->getClassById($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});