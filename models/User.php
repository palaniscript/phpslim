<?php

/**
 * This is an example of User Class using PDO
 *
 */

namespace models;
use lib\Core;
use PDO;

class User {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
		//$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	// Get all users
	public function getUsers() {
		$r = array();		

		$sql = "SELECT * FROM users";
		$stmt = $this->core->dbh->prepare($sql);
		//$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	//Authenticate Session
	public function authenticateSession($token){
		if($token!='' && $token!=null){
			$r = array();		

			$sql = "SELECT * FROM users WHERE token = '$token' AND token_expire > NOW()";
			$stmt = $this->core->dbh->prepare($sql);
			
			if ($stmt->execute()) {
				$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if(sizeof($r)){
					$tokenExpiration = date('Y-m-d H:i:s', time()+3600);
					$this->updateToken($r[0]['email'], $token, $tokenExpiration);
				}
				return sizeof($r);
			} else {
				return false;
			}		
			return $r;
		}else{
			return false;
		}
	}

	// Get user by the Id
	public function getUserById($id) {
		$r = array();		
		
		$sql = "SELECT nombre * evnt_usuario WHERE id=$id";
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
	public function getUserByLogin($email, $pass) {
		$r = array();		
		
		$sql = "SELECT * FROM users WHERE email='$email' AND password='$pass'";		
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$token = bin2hex(openssl_random_pseudo_bytes(16));
			$tokenExpiration = date('Y-m-d H:i:s', time()+3600);

			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
			if(sizeof($r)){
				$this->updateToken($r[0]['email'], $token, $tokenExpiration);
				$r[0]['token'] = $token;
				$r[0]['token_expire'] = $tokenExpiration;
			}
			else $r = 0;
		} else {
			$r = 0;
		}		
		return $r;
	}

	//Logout User
	public function logoutUser($email){
		$sql = "UPDATE users SET token = 'NULL', token_expire = 'NULL' WHERE email = '$email'";		
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$r = true;
		} else {
			$r = false;
		}		
		return $r;	
	}

	//Update user password
	public function updatePassword($id, $password){
		$sql = "UPDATE users SET password = '$password' WHERE id = '$id'";		
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$r = true;
		} else {
			$r = false;
		}		
		return $r;	
	}

	public function checkCurrentPassword($id, $curentPassword){
		$sql = "SELECT * FROM users WHERE password = '$curentPassword' AND id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		if ($stmt->execute()) {
			$r = sizeof($stmt->fetchAll(PDO::FETCH_ASSOC));
		} else {
			$r = false;
		}

		return $r;	
	}

	// Update Token
	public function updateToken($email, $token, $token_expire) {
		try {
			$sql = "UPDATE users SET token = '$token', token_expire = '$token_expire' WHERE email = '$email'";

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

	// Insert a new user
	public function insertUser($data) {
		try {
			$sql = "INSERT INTO user (name, email, password, role) 
					VALUES (:name, :email, :password, :role)";
			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute($data)) {
				return $this->core->dbh->lastInsertId();;
			} else {
				return '0';
			}
		} catch(PDOException $e) {
        	return $e->getMessage();
    	}
		
	}

	// Update the data of an user
	public function updateUser($data) {
		
	}

	// Delete user
	public function deleteUser($id) {
		
	}

}