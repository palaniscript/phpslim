<?php

namespace models;
use lib\Core;
use PDO;

class Section {

	protected $core;

	function __construct() {
		$this->core = Core::getInstance();
	}
	
	// Get all sections
	public function getAllSections() {
		$r = array();		

		$sql = "SELECT class_sections.*, classes.name FROM class_sections LEFT OUTER JOIN classes ON class_sections.class_id=classes.id ORDER BY class_sections.id DESC";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Add new section
	public function addSection($data) {
		try {
			if(isset($data['id'])){
				$id = $data['id'];
				$class = $data['class_id'];
				$section = $data['section'];
				$status = $data['status'];
				$sql = "UPDATE class_sections SET class_id='$class', section='$section', status='$status' WHERE id='$id'";	
			}else{
				$sql = "INSERT INTO class_sections (class_id, section, status, created_by, created_at) 
					VALUES (:class_id, :section, :status, :created_by, :created_at)";	
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

    // Delete section
	public function deleteSection($id) {
		$sql = "DELETE FROM class_sections WHERE id IN ($id)";
		$stmt = $this->core->dbh->prepare($sql);
		
		if ($stmt->execute()) {
			$r = 1;		   	
		} else {
			$r = 0;
		}		
		return $r;
	}

	// Get section by id
	public function getSectionById($id) {
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