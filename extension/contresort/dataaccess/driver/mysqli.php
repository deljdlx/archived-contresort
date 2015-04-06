<?php
namespace Contresort\DataAccess\Driver;


class MySQLi extends \MySQLI
{
	public function initialize($host, $user, $password='', $database=null, $port=3306) {
		$this->connect($host, $user, $password, $database, $port);
		return $this;
	}




	public function queryAndFetch($query) {
		$statement=$this->query($query);
		if($statement) {
			$rows=array();
			while($row=$statement->fetch_assoc()) {
				$rows[]=$row;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	public function queryAndFetchOne($query) {
		$statement=$this->query($query);
		if($statement) {
			if($row=$statement->fetch_assoc()) {
				return $row;
			}
		}
		return false;

	}



}