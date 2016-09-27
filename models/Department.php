<?php

namespace models;
use lib\Core;
use PDO;

class Department {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all departments
	public function getAllDepartments() {
		$r = array();		

		$sql = "SELECT  @a:=@a+1 serial_number, id, 
        name, status FROM departments ,
        (SELECT @a:= 0) AS a ORDER BY id DESC";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Add new department
	public function addDepartment($data) {
		try {
			if(isset($data['id'])){
				$id = $data['id'];
				$name = $data['name'];
				$status = $data['status'];
				$sql = "UPDATE departments SET name='$name', status='$status' WHERE id='$id'";	
			}else{
				$sql = "INSERT INTO departments (name, status, created_by, created_at) 
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

    // Delete department
	public function deleteDepartment($id) {
		$sql = "DELETE FROM departments WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = 1;		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get department by id
	public function getDepartmentById($id) {
		$sql = "SELECT * FROM departments WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	
}