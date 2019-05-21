<?php

/**
 * This is an example of User Class using PDO
 *
 */

namespace models;
use lib\Core;
use PDO;

class User {

	protected $core, $db, $columns, $columnNames;

	function __construct() {
		$this->core = Core::getInstance();
		$this->db = 'users';
	}
	
	//Authenticate Session
	public function authenticateSession($token){
		if($token!='' && $token!=null){
			$r = array();		

			$secret = 'abC123!';
			$verify = verifyJWT('sha256', $token, $secret);	
			return $verify;
		}else{
			return false;
		}
	}

	// Update the existing record
	public function updatePassword($data) {
		try {
			$passcode = $data['passcode'];
			$mobile = $data['mobile'];
			$sql = "UPDATE users SET passcode = '$passcode' WHERE mobile='$mobile'";
			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute($data)) {
				return true;
			} else {
				return '0';
			}
		} catch(PDOException $e) {
	    	return $e->getMessage();
		}
	}

	// Get user by the Id
	public function getUserById($id) {
		$r = array();		
		
		$sql = "SELECT id, first_name, last_name, email, status, token, token_expire FROM users WHERE id=$id";
		$stmt = $this->core->dbh->prepare($sql);
		//$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get user by the Login
	public function getUserByLogin($mobile, $pass, $app) {
		$r = array();		
		
		$sql = "SELECT id, name, mobile, points, version FROM users WHERE mobile='$mobile' AND passcode='$pass'";		
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			$tokenExpiration = date('Y-m-d H:i:s', time()+3600);

			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
			if(sizeof($r)){
				$r[0]['token'] = $token;
				$r[0]['token_expire'] = $tokenExpiration;

				$app->response->setBody(generateJWT($r[0]));
				$res = $app->response();
				$res->status(200);
			}else{
				$res = $app->response();
				$res->status(401);
			}
		} else {
			$res = $app->response();
			$res->status(401);
		}		
		return $res;
	}

	//Logout User
	public function logoutUser($mobile){
		$sql = "UPDATE users SET token = 'NULL', token_expire = 'NULL' WHERE mobile = '$mobile'";		
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$r = true;
		} else {
			$r = false;
		}		
		return $r;	
	}

	public function checkCurrentPassword($id, $curentPassword){
		$sql = "SELECT id, first_name, last_name, email, status, token, token_expire FROM users WHERE password = '$curentPassword' AND id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$r = sizeof($stmt->fetchAll(PDO::FETCH_ASSOC));
		} else {
			$r = false;
		}

		return $r;	
	}

	// Update Token
	public function updateToken($username, $token, $token_expire) {
		try {
			$sql = "UPDATE users SET token = '$token', token_expire = '$token_expire' WHERE username = '$username'";

			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute()) {
				$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
			} else {
				$r = 0;
			}	
		} catch(PDOException $e) {
        	return $e->getMessage();
    	}
		
	}
}