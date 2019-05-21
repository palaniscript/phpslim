SlimMVC
=======

SlimMVC is the easiest and flexible way to create your PHP application using a MVC pattern.
SlimMVC use the PHP microframework [Slim Framework](http://www.slimframework.com/) and use the best practices collected in the slim community.

Getting Started
---------------
1. Get or download the project
2. Install it using Composer

Folder System
---------------
* lib/
    * Config.php (Class to store with config variables)
    * Core.php (Singleton PDO connection to the DB)   
* models/
* public/
* routers/
	* name.router.php (routes by functionalities)
* templates/

### lib/

Here we have the core classes of the connection with the DB

### models/

Add the model classes here.
We are using PDO for the Database.

Example of class:

Stuff.php

```php
class Stuff {

    protected $core;

    function __construct() {
        $this->core = Core::getInstance();
    }

    // Get all stuff
    public function getAllStuff() {
        $r = array();

        $sql = "SELECT * FROM stuff";
        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute()) {
            $r = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }
}
```

### public/

All the public files:
* Images, CSS and JS files
* index.php

### routers/

All the files with the routes. Each file contents the routes of an specific functionality.
It is very important that the names of the files inside this folder follow this pattern: name.router.php

Example of router file:

stuff.router.php

```php
// Get stuff
$app->get('/stuff', function () use ($app) {
    echo 'This is a GET route';
});

//Create user
$app->post('/stuff', function () use ($app) {
    echo 'This is a POST route';
});

// PUT route
$app->put('/stuff', function () {
    echo 'This is a PUT route';
});

// DELETE route
$app->delete('/stuff', function () {
    echo 'This is a DELETE route';
});
```

### templates/

All the Twig templates.

How to Contribute
-----------------
### Pull Requests

1. Fork the SlimMVC repository
2. Create a new branch for each feature or improvement
3. Send a pull request from each feature branch to the develop branch

Sample router

```
<?php
// Get all customers
$app->get('/customers', function () use ($app) {
	$start = $app->request()->get('_start') ? $app->request()->get('_start') : 0;
	$limit = $app->request()->get('_limit') ? $app->request()->get('_limit') : 10;
	$orderBy = $app->request()->get('_sort');
	$orderDirection = $app->request()->get('_order');
    
	$oStuff = new models\Customer();
	$response = $oStuff->getRecords($start, $limit, $orderBy, $orderDirection);
	$app->contentType('application/json');
	echo json_encode($response);
});

// Get single customer
$app->get('/customer/:id', function ($customer_id) use ($app) {
	$oStuff = new models\Customer();
	$response = $oStuff->getSingleRecord($customer_id);
	$app->contentType('application/json');
	echo json_encode($response);
});

// Add new customer
$app->post('/customer', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);

	$oStuff = new models\Customer();
	$response = $oStuff->addRecord($data);
	$app->contentType('application/json');
	echo json_encode($response);
});

// Edit existing customer
$app->put('/customer/:id', function ($customer_id) use ($app) {
	$data = json_decode($app->request->getbody(),true);

	$oStuff = new models\Customer();
	$response = $oStuff->editRecord($customer_id, $data);
	$app->contentType('application/json');
	echo json_encode($response);
});

// Delete existing customer
$app->delete('/customer/:id', function ($customer_id) use ($app) {
	$oStuff = new models\Customer();
	$response = $oStuff->deleteRecord($customer_id);
	$app->contentType('application/json');
	echo json_encode($response);
});

// Check email exists
$app->post('/email-exists', function () use ($app) {
	$data = json_decode($app->request->getbody(),true);

	$oStuff = new models\Customer();
	$response = $oStuff->emailExists($data);
	$app->contentType('application/json');
	echo json_encode($response);
});
```


Sample Modal

```
<?php

namespace models;
use lib\Core;
use PDO;

class Customer {

	protected $core, $db, $columns, $columnNames;

	function __construct() {
		$this->core = Core::getInstance();
		$this->db = 'customers';
	}

	public function getRecords($start, $limit, $orderBy, $orderDirection){
		$sql = "SELECT * from customers";
		if($orderBy != '' && $orderDirection != ''){
			$sql .= " ORDER BY " .$orderBy . " " .$orderDirection;
		}
		$sql .= " LIMIT " . $start ."," . $limit;
		try{
			$stmt = $this->core->dbh->prepare($sql);
			if ($stmt->execute()) {
				$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$customers = [];
				foreach ($records as $row) {
					 array_push($customers, array('id' => (int)$row['id'], 'name' => $row['name'], 'mobile'=> $row['mobile'], 'email'=> $row['email'], 'address'=>$row['address'], 'dob'=>$row['dob'], 'created'=> date('d-m-Y h:i A', strtotime($row['created'])), 'updated'=> date('d-m-Y h:i A', strtotime($row['updated']))));
				}
				$total = $this->getTotalRecords();
				return array("data"=>$customers,"total"=>(int)$total);
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function getSingleRecord($customer_id){
		$sql = "SELECT * from customers WHERE id = :id";
		try{
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->bindParam("id", $customer_id);
			if ($stmt->execute()) {
				$record = $stmt->fetch(PDO::FETCH_ASSOC);
				return $record;
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function addRecord($data){
		$sql = "INSERT INTO customers (name, mobile, email, dob, address, created) VALUES (:name, :mobile, :email, :dob, :address, :created)";
		try{
			$created = date("Y-m-d H:i:s");
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->bindParam("name", $data['name']);
			$stmt->bindParam("mobile", $data['mobile']);
			$stmt->bindParam("email", $data['email']);
			$stmt->bindParam("dob", $data['dob']);
			$stmt->bindParam("address", $data['address']);
			$stmt->bindParam("created", $created);

			if ($stmt->execute()) {
				return $data;
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function editRecord($customer_id, $data){
		$sql = "UPDATE customers SET name=:name, mobile=:mobile, email=:email, dob=:dob, address=:address, updated=:updated WHERE id=:id";
		try{
			$updated = date("Y-m-d H:i:s");
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->bindParam("id", $customer_id);
			$stmt->bindParam("name", $data['name']);
			$stmt->bindParam("mobile", $data['mobile']);
			$stmt->bindParam("email", $data['email']);
			$stmt->bindParam("dob", $data['dob']);
			$stmt->bindParam("address", $data['address']);
			$stmt->bindParam("updated", $updated);

			if ($stmt->execute()) {
				return $data;
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function deleteRecord($customer_id){
		$sql = "DELETE FROM customers WHERE id=:id";
		try{
			$updated = date("Y-m-d H:i:s");
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->bindParam("id", $customer_id);
			
			if ($stmt->execute()) {
				return $customer_id;
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function getTotalRecords(){
		$sql = "SELECT count(*) as total from customers";
		try{
			$stmt = $this->core->dbh->prepare($sql);
			
			if ($stmt->execute()) {
				$record = $stmt->fetch(PDO::FETCH_ASSOC);
				return $record['total'];
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}

	public function emailExists($data){
		$email = $data['email'];
		$sql = "SELECT * from customers WHERE email = :email";
		try{
			$stmt = $this->core->dbh->prepare($sql);
			$stmt->bindParam("email", $email);
			if ($stmt->execute()) {
				$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
				return $records.length;
			}else{
				$err_info = $stmt->errorInfo();
				return '{"error": {"text": '. $err_info[2].'}}';
			}
		} catch(PDOException $e){
			return '{"error": {"text": '. $e->getMessage().'}}';
		}
	}
}
```
