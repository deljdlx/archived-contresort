<?php

namespace Contresort;


class Environment
{

	public $method;
	public $url=false;
	public $uri=false;

	public $session=array();
	public $cookie=array();
	public $get=array();
	public $post=array();

	public $request;


	public function __construct() {

		$mode=php_sapi_name();

		if($mode=='cli') {
			$this->method='cli';
		}
		else {
			$this->method=strtolower($_SERVER['REQUEST_METHOD']);
			$this->post=$_POST;
			$this->get=$_GET;
			$this->cookie=$_COOKIE;

			$this->loadURL();
			$this->loadRequest();
		}

		if(isset($_SESSION)) {
			$this->session=$_SESSION;
		}
	}

	public function setURL($url) {
		$this->url=$url;
		return $this;
	}

	public function setGetVariables(array $get) {
		$this->get=$get;
		return $this;
	}

	public function setPostVariables(array $post) {
		$this->post=$post;
		return $this;
	}

	public function setSessionVariables(array $session) {
		$this->session=$session;
		return $this;
	}



	public function loadURL($url=null) {
		if($url) {
			$data=parse_url($url);
			$this->url=$url;
			$this->uri=$data['path'].'?'.$data['query'];
			return $this;
		}
		if(isset($_SERVER)) {
			if(isset($_SERVER['SERVER_PROTOCOL']) && isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI'])) {
				$protocol = strtolower(preg_replace('`(.*?)/.*`', '$1', $_SERVER['SERVER_PROTOCOL']));
				$this->url = $protocol . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
				$this->uri=$_SERVER['REQUEST_URI'];
			}
		}
		return $this;
	}


	public function loadRequest($request=null) {
		if(!$request) {
			$this->request=new HTTP\Request();
		}
		else {
			$this->request=$request;
		}

		return $this;
	}

	public function getRequest() {
		return $this->request;
	}


	public function getMethod() {
		return $this->method;
	}


	public function getURL() {
		return $this->url;
	}

	public function getURI() {
		return $this->uri;
	}
}
