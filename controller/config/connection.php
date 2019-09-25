<?php
Class dbObj{
	/* Database connection start */
	private $servername = "localhost";
	private $username = "root";
	private $password = "6102t0bgruf";
	private $dbname = "is2med_db";
	public $conn;

	function getConn() {
		$con = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

		/* check connection */
		if ($con->connect_error) {
			die("Connection failed: " . $con->connect_error);
		} else {
			$this->conn = $con;
		}
		return $this->conn;
	}
}

?>