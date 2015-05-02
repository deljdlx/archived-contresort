<?php
namespace Contresort\DataAccess\Driver;


class MySQLi extends \MySQLI
{

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