<?php
namespace Contresort;

class Request
{
	public  $values;

	public function __construct($values=null) {
		if(!$values) {
			$values=getallheaders();
		}
		$this->values=$values;
	}
}