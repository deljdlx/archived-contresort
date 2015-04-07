<?php

namespace Contresort\Route;

class Rule
{

	protected $validator;
	protected $method=null;
	protected $parameters=array();


	public function __construct($method, $validator) {
		if(is_string($this->method)) {
			$this->method=strtolower($method);
		}
		else {
			$this->method=$method;
		}
		$this->validator=$validator;
	}

	public function getMethod() {
		return $this->method;
	}

	public function validate($application) {

		$environment=$application->getEnvironment();

		if($environment->getMethod()!=$this->method && $this->method!==null) {
			return false;
		}

		$string='';
		if($this->getMethod()!='cli') {
			$string=$environment->getURL();
		}

		$returnValue=false;
		if(is_string($this->validator)) {
			$returnValue=preg_match_all($this->validator, $string, $matches);

			array_shift($matches);
			$skipNext=false;

			if($returnValue) {
				//remove doublons if named pattern
				foreach ($matches as $index => $match) {
					if ($skipNext) {
						$skipNext = false;
						continue;
					}

					if (is_string($index)) {
						$skipNext = true;
					} else {
						$skipNext = false;
					}
					$this->parameters[$index] = $match[0];
				}
			}
		}
		else if(is_callable($this->validator)) {
			$closure=$this->validator->bindTo($this, $this);
			return $closure->__invoke($application);
		}

		return $returnValue;
	}

	public function getParameters() {
		return $this->parameters;
	}


}
