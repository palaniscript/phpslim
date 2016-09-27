<?php

// Get all departments
$app->get('/departments', function () use ($app) {	
	
	$dept = new models\Department();
	$departments = $dept->getAllDepartments();
	$app->contentType('application/json');
	echo json_encode($departments);
});

//Create department
$app->post('/department', function () use ($app) {	
	//var_dump($app->request()->post('data'));
	$department = json_decode($app->request->getbody(),true);
	$dept = new models\Department();
	echo $dept->addDepartment($department);
});

// DELETE department
$app->delete('/department', function () use ($app) {	
	$department = json_decode($app->request->getbody(),true);
    $dept = new models\Department();
	$departments = $dept->deleteDepartment($department['id']);
	$app->contentType('application/json');
	echo json_encode($departments);
});

// Get department by id
$app->post('/getDepartment', function () use ($app) {	
	$department = json_decode($app->request->getbody(),true);
    $dept = new models\Department();
	$departments = $dept->getDepartmentById($department['id']);
	$app->contentType('application/json');
	echo json_encode($departments);
});