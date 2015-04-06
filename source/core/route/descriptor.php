<?php

namespace Contresort\Route;

use Contresort\Environment;

class Descriptor
{
	protected $name;
	protected $rule;
	protected $actions=array();


	protected $selected=false;

	protected $builder;

	protected $output='';

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


	public function addAction($action) {
		$this->actions[]=$action;
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
		if($this->selected()) {
			$parameters=$this->rule->getParameters();
			foreach ($this->actions as $action) {
				$closure=$action->bindTo($application, $application);
				$this->output=call_user_func_array(array($closure, '__invoke'), $parameters);
			}
			return true;
		}
		else {
			return false;
		}
	}

	public function getOutput() {
		return $this->output;
	}

}