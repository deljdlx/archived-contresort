<?php
namespace Contresort;


use Contresort\Route\Descriptor;
use Contresort\Route\Rule;

class Application
{

	protected $filepath;

	protected $routingRules=array();
	protected $routingRulesByName=array();

	protected $output='';

	protected $environment;

	protected $headers=array();

	public function __construct($namespace, $filepath=null) {
		$this->namespace=$namespace;
		if($filepath===null) {
			$filepath=$this->findFilepathRoot();
		}
		$this->filepath=$filepath;

		$this->loadEnvironment();

	}

	public function loadEnvironment($environment=null) {
		if($environment===null) {
			$this->environment = new Environment();
		}
		else {
			$this->environment = $environment;
		}
		return $this;
	}




	public function getEnvironment() {
		return $this->environment;
	}



	public function getURL($name, $parameters=array()) {
		if(isset($this->routingRulesByName[$name])) {

			$descriptor=$this->routingRulesByName[$name];
			$url=$descriptor->buildURL($parameters);
			return $url;
		}
		else {
			return false;
		}
	}



	public function createRouteDescriptor($method, $validator) {
		$descriptor=new Descriptor(
			$method,
			new Rule($validator)
		);

		return $descriptor;
	}


	public function addRouteDescriptor($descriptor) {
		$this->routingRules[]=$descriptor;
		return $this;
	}


	public function route($method, $validator) {
		$descriptor=$this->createRouteDescriptor($method, $validator);
		$this->addRouteDescriptor($descriptor);
		return $descriptor;
	}




	public function get($validator) {
		return $this->route('get', $validator);
	}


	public function post($validator) {
		return $this->route('post', $validator);
	}

	public function cli($validator) {
		return $this->route('cli', $validator);
	}


	public  function mapRoutingRules() {
		foreach ($this->routingRules as $index=>$descriptor) {
			if($name=$descriptor->name()) {
				$this->routingRulesByName[$descriptor->name()] = $descriptor;
			}
			else {
				$this->routingRulesByName[$index] = $descriptor;
			}
		}
		return $this;
	}


	public function run() {

		$this->mapRoutingRules();

		foreach ($this->routingRules as $descriptor) {
			$result=$descriptor->run($this);
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

