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

	protected $status=0;

	protected $headers=array();

	protected $exitActions=array();
	protected $postActions=array();
	protected $preActions=array();


	protected $environment=null;
	protected $HTTPResponseDriver=null;

	protected $requestMethod;



	public function __construct($namespace, $filepath=null) {
		if($filepath===null) {
			$filepath=$this->findFilepathRoot();
		}
		parent::__construct($namespace, $filepath);

		$this->loadEnvironment();
		$this->requestMethod=$this->getEnvironment()->getMethod();

		if($this->requestMethod!='cli') {
			$this->setHTTPResponseDriver();
		}


		$this->addPostAction(function() {
			if($this->requestMethod!='cli') {
				$this->sendHeaders();
			}
		});
	}


    //@todo
	public function setHTTPResponseDriver($driver='\Contresort\HTTP\Response') {
		if($driver) {
			$this->HTTPResponseDriver=null;
		}
		else if(class_exists($driver)) {
			$this->HTTPResponseDriver=new $driver;
		}
		return $this;
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


	/**
	 * @return \Contresort\Environment
	 */
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

	public function get($validator, $action=null) {
		$routeDescriptor=$this->createRoute('get', $validator);
		if($action) {
			$routeDescriptor->addAction($action);
		}
		return $routeDescriptor;
	}

	public function post($validator, $action=null) {
		$routeDescriptor=$this->createRoute('post', $validator);
		if($action) {
			$routeDescriptor->addAction($action);
		}
		return $routeDescriptor;
	}

	public function cli($validator, $action=null) {
		$routeDescriptor=$this->createRoute('cli', $validator);
		if($action) {
			$routeDescriptor->addAction($action);
		}
		return $routeDescriptor;
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

		if(!empty($this->preActions)) {
			$this->executeStack($this->preActions);
		}


		foreach ($this->routingRules as $descriptor) {
			$descriptor->runBefore($this);
			if($descriptor->isValid($this)) {
				$descriptor->selected(true);
				$this->selectedRoute=$descriptor;

				$routeDescriptor=$descriptor->run($this);

				if($routeDescriptor!==false) { //route execution valid
					if($this->requestMethod!='cli') {
						$this->setHTTPCacheHeaders($routeDescriptor);
					}
					break;
				}
			}
		}

		if($this->selectedRoute) {
			$this->selectedRoute->runAfter($this);
		}

		if(!empty($this->postActions)) {
			$this->executeStack($this->postActions);
		}

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

	/**
	 * @param \Contresort\Route\Descriptor $routeDescriptor
	 */
	public function setHTTPCacheHeaders($routeDescriptor) {
		//here we go, true cache control algo

		if($routeDescriptor->noStore()) {
			$this->addHeader('Cache-control', 'no-store, no-cache, must-revalidate');
			return $this;
		}

		$cacheControlName='Cache-control';
		$cacheControlValue='';
		if($routeDescriptor->noCache()) {
			$cacheControlValue='no-cache, ';
		}
		if($routeDescriptor->cacheVisibility()=='public') {
			$cacheControlValue='public, ';
		}
		else {
			$cacheControlValue='private, ';
		}

		//$this->addHeader('Last-Modified', gmdate("D, d M Y H:i:s", time()-30).' GMT');


		if($date=$routeDescriptor->expires()) {
			$this->addHeader('Expires', $date);
		}

		if($duration=$routeDescriptor->maxAge()) {
			$cacheControlValue.='must-revalidate, max-age='.$duration;
		}
		else {
			$cacheControlValue=substr($cacheControlValue, -2);
		}

		//$this->addHeader($cacheControlName, $cacheControlValue);




		if($eTag=$routeDescriptor->eTag()) {
			$this->addHeader('ETag', $eTag);
		}

		return $this;
	}




	public function addHeader($name, $value) {
		$this->headers[$name]=$value;
	}

	public function sendHeaders() {
		if($this->HTTPResponseDriver) {
			$this->HTTPResponseDriver->sendHeaders($this->headers);
		}
		else {
			foreach ($this->headers as $name => $value) {
				header($name.': '.$value);
			}
		}
		return $this;
	}


	public function stop() {
		$this->executeStack($this->exitActions);
		return $this;
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

