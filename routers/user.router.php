<?php

// Get user
$app->get('/user', function () use ($app) {	
	
	$oLaboratory = new models\User();
	$users = $oLaboratory->getUsers();
	$app->contentType('application/json');
	echo json_encode($users);
});

//Create user
$app->post('/user', function () use ($app) {	
	//var_dump($app->request()->post('data'));
	$user = json_decode($app->request()->post('data'), true);	
	$user['password'] = hash("sha1", $user['password']);	
	$oUser = new User ();
	echo $oUser->insertUser($user);
});

// LOGIN GET user by email and passwordS
$app->post('/login', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$email = $data['email'];
	$pass = md5($data['password']);

	$oUser = new models\User();

	echo json_encode($oUser->getUserByLogin($email, $pass), true);
});

// LOGIN GET user by email and passwordS
$app->post('/logout', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$email = $data['email'];

	$oUser = new models\User();
	echo json_encode($oUser->logoutUser($email), true);
});


// PUT route
$app->put('/user', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$id = $data['id'];
	$currentPassword = md5($data['currentPassword']);
	$newPassword = md5($data['newPassword']);

	$oUser = new models\User();
	if($oUser->checkCurrentPassword($id, $currentPassword)){
		echo json_encode($oUser->updatePassword($id, $newPassword), true);	
	}else{
		echo "invalid-old-password";
	}
	
});

// DELETE route
$app->delete('/user', function () {
    echo 'This is a DELETE route';
});