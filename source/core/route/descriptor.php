<?php

namespace Contresort\Route;

use Contresort\Environment;

class Descriptor
{
	protected $rule;
	protected $method;
	protected $actions=array();

	public function __construct($method, $rule, $action) {
		$this->method=strtolower($method);
		$this->rule=$rule;
		$this->actions[]=$action;
	}


	public function also($action) {
		$this->actions[]=$action;
		return $this;
	}

	public function getMethod() {
		return $this->method;
	}

	public function execute($application) {
		$environment=$application->getEnvironment();

		if($environment->getMethod()!=$this->method) {
			return false;
		}


		$string='';
		if($this->getMethod()=='get') {
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