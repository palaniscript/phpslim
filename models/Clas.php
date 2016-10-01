<?php

namespace models;
use lib\Core;
use PDO;

class Clas {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all classes
	public function getAllClasses() {
		$r = array();		

		$sql = "SELECT  @a:=@a+1 serial_number, id, 
        name, status FROM classes ,
        (SELECT @a:= 0) AS a ORDER BY id DESC";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Add new class
	public function addClass($data) {
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
		$sql = "DELETE FROM classes WHERE id IN ($id)";
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