<?php
namespace Contresort\HTTP;

class Cache
{

	protected $HTTPResponseDriver;

	public function __construct($HTTPResponseDriver) {
		$this->HTTPResponseDriver=$HTTPResponseDriver;
	}


}