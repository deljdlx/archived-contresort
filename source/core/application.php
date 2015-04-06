<?php
namespace Contresort;


use Contresort\Route\Descriptor;
use Contresort\Route\Rule;

class Application
{

	protected $filepath;
	protected $routingRules=array();

	protected $output='';

	protected $environment;

	protected $headers=array();

	public function __construct($namespace, $filepath=null) {
		$this->namespace=$namespace;
		if($filepath===null) {
			$filepath=$this->findFilepathRoot();
		}
		$this->filepath=$filepath;


		$this->environment=new Environment();
	}




	public function getEnvironment() {
		return $this->environment;
	}

	public function get($validator, $action=null, $name=null) {
		$descriptor=new Descriptor(
			'get',
			new Rule($validator),
			$action
		);
		$this->addRouteDescriptor($descriptor, $name);

		return $descriptor;
	}


	public function post($validator, $action=null, $name=null) {
		$descriptor=new Descriptor(
			'post',
			new Rule($validator),
			$action
		);
		$this->addRouteDescriptor($descriptor, $name);

		return $descriptor;
	}

	public function cli($validator, $action=null, $name=null) {
		$descriptor=new Descriptor(
			'cli',
			new Rule($validator),
			$action
		);
		$this->addRouteDescriptor($descriptor, $name);

		return $descriptor;
	}



	public function addRouteDescriptor($descriptor, $name=null) {
		if($name===null) {
			$this->routingRules[]=$descriptor;
		}
		else {
			$this->routingRules[$name]=$descriptor;
		}

		return $this;
	}

	public function run() {
		foreach ($this->routingRules as $name=>$descriptor) {
			$result=$descriptor->execute($this);
			if($result!==false) {
				break;
			}
		}

		if($this->getEnvironment()->getMethod()!='cli') {
			$this->sendHeaders();
		}

		return $this;
	}

	public function addHeader($name, $value) {
		$this->headers[$name]=$value;
	}

	public function sendHeaders() {
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value);
		}
		return $this;
	}


	public function getOutput() {
		return $this->output;
	}



	protected function findFilepathRoot($callstackSize=10) {
		$applicationData=debug_backtrace($callstackSize);

		$className=get_class($this);

		foreach($applicationData as $key=>$data) {
			if($data['class']==$className && $data['function']=='__construct') {
				return str_replace('\\', '/', dirname($data['file']));
			}
		}

		return false;
	}


}

