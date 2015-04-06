<?php

namespace Contresort\Route;

class Rule
{

	protected $validator;
	protected $parameters=array();


	public function __construct($validator) {
		$this->validator=$validator;
	}


	public function validate($string) {
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
			return $closure->__invoke();
		}

		return $returnValue;
	}

	public function getParameters() {
		return $this->parameters;
	}


}
