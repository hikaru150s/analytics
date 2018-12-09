<?php
class DB {
	private $host;
	private $user;
	private $pass;
	private $database;
	
	public function __construct() {
		$this->host			= 'localhost';
		$this->user			= 'root';
		$this->pass			= '';
		$this->database		= 'mtemp';
	}
	
	public function query($q) {
		$db = new mysqli($this->host, $this->user, $this->pass, $this->database);
		
		if ($r = $db->query($q)) {
			$result = array();
			while ($o = $r->fetch_object()) {
				$result[] = $o;
			}
			return $result;
		} else {
			return null;
		}
	}
}
