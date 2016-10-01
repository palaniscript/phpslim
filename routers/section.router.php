<?php

// Get all sections
$app->get('/sections', function () use ($app) {	
	
	$class = new models\Section();
	$classes = $class->getAllSections();
	$app->contentType('application/json');
	echo json_encode($classes);
});

//Create a section
$app->post('/section', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
	$class = new models\Section();
	echo $class->addSection($data);
});

// DELETE a section
$app->delete('/section', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Section();
	$classes = $class->deleteSection($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});

// Get class and section by id
$app->post('/getSection', function () use ($app) {	
	$data = json_decode($app->request->getbody(),true);
    $class = new models\Section();
	$classes = $class->getSectionById($data['id']);
	$app->contentType('application/json');
	echo json_encode($classes);
});