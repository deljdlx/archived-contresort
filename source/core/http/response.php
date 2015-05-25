<?php
namespace Contresort\HTTP;

class Response
{

	protected $headers=array();
	protected $content='';

	public function __construct($content='') {
		$this->content($content);
	}

	public function addHeader($name, $value) {
		$this->headers[$name]=$value;
		return $this;
	}


	public function sendHeaders(array $headers=null) {
		if($headers) {
			$this->headers=$headers;
		}

		foreach ($this->headers as $name => $value) {
			header($name.': '.$value);
		}
		return $this;
	}

	public function setContent($content) {
		$this->content=$content;
		return $this;
	}

	public function getContent() {
		return $this->content;
	}

}