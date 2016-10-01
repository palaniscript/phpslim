<?php

// Get all classes
$app->get('/classes', function () use ($app) {	
	
	$class = new models\Clas();
	$classes = $class->getAllClasses();
	$app->contentType('application/json');
	echo json_encode($classes);
});

//Create class
$app->post('/class', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
	$class = new models\Clas();
	echo $class->addClass($data);
});

// DELETE class
$app->delete('/class', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Clas();
	$classes = $class->deleteClass($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});

// Get class by id
$app->post('/getClass', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Clas();
	$classes = $class->getClassById($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});