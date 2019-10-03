<?php
Class dbObj{
	/* Database connection start */
	private $servername = "localhost";
	private $username = "Usuario";
	private $password = "is2med";
	private $dbname = "is2med";
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