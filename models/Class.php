<?php

namespace models;
use lib\Core;
use PDO;

class Class {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all classes
	public function getAllClasses() {
		$r = array();		

		$sql = "SELECT  @a:=@a+1 serial_number, id, 
        class, section, status FROM class_sections ,
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
				$class = $data['class'];
				$section = $data['section'];
				$sql = "UPDATE class_sections SET class='$class', section='$section' WHERE id='$id'";	
			}else{
				$sql = "INSERT INTO class_sections (class, section, status, created_by, created_at) 
					VALUES (:name, :section, :status, :created_by, :created_at)";	
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

    // Delete Class
	public function deleteType($id) {
		$sql = "DELETE FROM class_sections WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = 1;		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get class by id
	public function getTypeById($id) {
		$sql = "SELECT * FROM class_sections WHERE id = '$id'";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	
}