<?php
$url = '/users';

// Get single record
$app->get($url . '/:id', function ($id) use ($app) {
	$oStuff = new models\User();
	$response = $oStuff->getRecord($id);
	$app->contentType('application/json');
	echo json_encode($response);
});

// PUT route
$app->post('/update-password', function () use ($app) {
	$data = json_decode($app->request->getBody(), true);	
	$oStuff = new models\User();
	echo $oStuff->updatePassword($data);
});

// LOGIN GET user by email and passwordS
$app->post('/authentication', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$mobile = $data['mobile'];
	$pass = $data['passcode'];

	$oUser = new models\User();

	return $oUser->getUserByLogin($mobile, $pass, $app);
});

// LOGIN GET user by session
$app->post('/loginBySession', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$token = $data['token'];
	$oUser = new models\User();

	if($oUser->authenticateSession($token)){
		echo json_encode($oUser->getUserById($data["id"]), true);
	}else{
		echo json_encode($oUser->authenticateSession($token), true);	
	}
});

// LOGIN GET user by mobile and passwordS
$app->post('/logout', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);
	
	$mobile = $data['mobile'];

	$oUser = new models\User();
	echo json_encode($oUser->logoutUser($mobile), true);
});