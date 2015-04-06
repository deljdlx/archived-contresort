<?php

namespace Contresort\Route;

use Contresort\Environment;

class Descriptor
{
	protected $name;
	protected $rule;

	protected $preActions=array();
	protected $actions=array();
	protected $postActions=array();


	protected $selected=false;

	protected $builder;

	public function __construct($rule) {
		$this->rule=$rule;
	}


	public function selected($value=null) {
		if($value!==null) {
			$this->selected=$value;
			return $this;
		}
		else {
			return $this->selected;
		}
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



	public function addPreAction($action) {
		$this->preActions[]=$action;
		return $this;
	}

	public function addAction($action) {
		$this->actions[]=$action;
		return $this;
	}

	public function addPostAction($action) {
		$this->postActions[]=$action;
		return $this;
	}


	public function isValid($application) {
		if($this->rule->validate($application)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function run($application) {
		$parameters=$this->rule->getParameters();
		foreach ($this->actions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), $parameters);
			if(!$result) {
				break;
			}
		}
		return $this;
	}

	public function runAfter($application) {
		$parameters=$this->rule->getParameters();
		foreach ($this->postActions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), $parameters);
			if(!$result) {
				break;
			}
		}
		return $this;
	}

	public function runBefore($application) {

		foreach ($this->preActions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), array());
			if(!$result) {
				break;
			}
		}
		return $this;
	}


}