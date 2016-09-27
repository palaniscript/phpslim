<?php

namespace models;
use lib\Core;
use PDO;

class FeeType {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all years
	public function getAllTypes() {
		$r = array();		

		$sql = "SELECT  @a:=@a+1 serial_number, id, 
        name, status FROM feetypes ,
        (SELECT @a:= 0) AS a ORDER BY id DESC";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Add new years
	public function addType($data) {
		try {
			if(isset($data['id'])){
				$id = $data['id'];
				$name = $data['name'];
				$status = $data['status'];
				$sql = "UPDATE feetypes SET name='$name', status='$status' WHERE id='$id'";	
			}else{
				$sql = "INSERT INTO feetypes (name, status, created_by, created_at) 
					VALUES (:name, :status, :created_by, :created_at)";	
			}
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

    // Delete years
	public function deleteType($id) {
		$sql = "DELETE FROM feetypes WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = 1;		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get year by id
	public function getTypeById($id) {
		$sql = "SELECT * FROM feetypes WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	
}