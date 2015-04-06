<?php
namespace Contresort;


use Contresort\Route\Descriptor;
use Contresort\Route\Rule;

class Application extends Extension
{

	protected $filepath;

	protected $routingRules=array();
	protected $routingRulesByName=array();

	protected $selectedRoute;

	protected $output='';

	protected $environment;
	protected $status=0;

	protected $headers=array();

	protected $exitActions=array();
	protected $postActions=array();
	protected $preActions=array();


	public function __construct($namespace, $filepath=null) {
		if($filepath===null) {
			$filepath=$this->findFilepathRoot();
		}
		parent::__construct($namespace, $filepath);
		$this->loadEnvironment();


		$this->addPostAction(function() {
			if($this->getEnvironment()->getMethod()!='cli') {
				$this->sendHeaders();
			}
		});

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




	//routing=========================================================
	public function buildURL($name, $parameters = array()) {
		if (isset($this->routingRulesByName[$name])) {

			$descriptor = $this->routingRulesByName[$name];
			$url = $descriptor->buildURL($parameters);
			return $url;
		} else {
			return false;
		}
	}

	public function createRouteDescriptor($method, $validator) {
		$descriptor = new Descriptor(
			new Rule($method, $validator)
		);
		return $descriptor;
	}

	public function addRouteDescriptor($descriptor) {
		$this->routingRules[] = $descriptor;
		return $this;
	}

	public function createRoute($method, $validator) {
		$descriptor = $this->createRouteDescriptor($method, $validator);
		$this->addRouteDescriptor($descriptor);
		return $descriptor;
	}

	public function get($validator) {
		return $this->createRoute('get', $validator);
	}

	public function post($validator) {
		return $this->createRoute('post', $validator);
	}

	public function cli($validator) {
		return $this->createRoute('cli', $validator);
	}

	public function mapRoutingRules() {
		foreach ($this->routingRules as $index => $descriptor) {
			if ($name = $descriptor->name()) {
				$this->routingRulesByName[$descriptor->name()] = $descriptor;
			} else {
				$this->routingRulesByName[$index] = $descriptor;
			}
		}
		return $this;
	}

	public function getRoute($name) {
		if (isset($this->routingRulesByName[$name])) {
			return $this->routingRulesByName[$name];
		} else {
			return false;
		}
	}


	public function getSelectedRoute() {
		return $this->selectedRoute;
	}

	//=======================================================



	public function run() {
		$this->mapRoutingRules();

		$this->executeStack($this->preActions);

		foreach ($this->routingRules as $descriptor) {
			$descriptor->runBefore($this);
			if($descriptor->isValid($this)) {
				$descriptor->selected(true);
				$this->selectedRoute=$descriptor;
				$descriptor->run($this);
				break;
			}
		}

		if($this->selectedRoute) {
			$this->selectedRoute->runAfter($this);
		}

		$this->executeStack($this->postActions);

		return $this;
	}

	public function addPostAction($callback) {
		$this->postActions[]=$callback;
		return $this;
	}
	public function addPreAction($callback) {
		$this->preActions[]=$callback;
		return $this;
	}
	public function addExitAction($callback) {
		$this->exitActions[]=$callback;
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




	public function stop() {
		$this->executeStack($this->exitActions);
		exit($this->getStatus());
	}


	public function executeStack($stack) {
		foreach($stack as $callback) {
			if(is_callable($callback)) {
				$closure=$callback->bindTo($this, $this);
				$returnValue=$closure->__invoke();
				if(!$returnValue) {
					break;
				}
			}
		}
		return $this;
	}


	public function getOutput() {
		return $this->output;
	}
	public function setOutput($string) {
		$this->output=$string;
		return $this;
	}


	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status=$status;
		return $this;
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

