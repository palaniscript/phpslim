<?php

namespace models;
use lib\Core;
use PDO;

class Student {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all students
	public function getAllStudents() {
		$r = array();		

		$sql = "SELECT * FROM students ORDER BY id DESC";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Add new student
	public function addStudent($data) {
		try {
			if(isset($data['id'])){
				$id = $data['id'];
				$name = $data['name'];
				$status = $data['status'];
				$sql = "UPDATE classes SET name='$name', status='$status' WHERE id='$id'";	
			}else{
				$sql = "INSERT INTO classes (name, status, created_by, created_at) 
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

    // Delete class
	public function deleteClass($id) {
		$sql = "DELETE FROM classes WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = 1;		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get class by id
	public function getClassById($id) {
		$sql = "SELECT * FROM classes WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	
}