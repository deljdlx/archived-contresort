<?php

namespace Contresort\Route;

use Contresort\Environment;

class Descriptor
{
	protected $name;
	protected $rule;
	protected $method;
	protected $actions=array();

	protected $builder;

	public function __construct($method, $rule) {
		if(is_string($this->method)) {
			$this->method=strtolower($method);
		}
		else {
			$this->method=$method;
		}

		$this->rule=$rule;
	}

	public function name($name=null) {
		if($name!==null) {
			$this->name = $name;
			return $this;
		}
		else {
			return $this->name;
		}
	}




	public function builder($builder) {
		$this->builder=$builder;
		return $this;
	}

	public function buildURL($parameters=array()) {
		if(is_callable($this->builder)) {
			return call_user_func_array(array($this->builder, '__invoke'), $parameters);
		}
		else if(is_string($this->builder)) {
			return $this->builder;
		}
		else {
			return false;
		}
	}


	public function execute($action) {
		$this->actions[]=$action;
		return $this;
	}

	public function getMethod() {
		return $this->method;
	}

	public function run($application) {
		$environment=$application->getEnvironment();


		if($environment->getMethod()!=$this->method && $this->method!==null) {
			return false;
		}


		$string='';
		if($this->getMethod()!='cli') {
			$string=$environment->getURL();
		}

		if($this->rule->validate($string))  {
			$parameters=$this->rule->getParameters();
			$results=array();
			foreach ($this->actions as $action) {
				$closure=$action->bindTo($application, $application);
				call_user_func_array(array($closure, '__invoke'), $parameters);
			}
			return $results;
		}
		else {
			return false;
		}

	}

}