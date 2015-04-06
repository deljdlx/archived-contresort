<?php

namespace Contresort\DataAccess;

class Connector
{

	protected $driver;

	public function __construct($driverName, $parameters=array()) {
		$className='\Contresort\DataAccess\Driver\\'.$driverName;
		$this->driver=new $className();

		$bindedParameters=bindParameters($parameters, $this->driver, 'initialize');

		call_user_func_array(array($this->driver, 'initialize'), $bindedParameters);
	}



	public function query($query) {
		return $this->driver->query($query);
	}

	public function queryAndFetch($query) {
		return $this->driver->queryAndFetch($query);
	}

	public function queryAndFetchOne($query) {
		return $this->driver->queryAndFetchOne($query);
	}

}

