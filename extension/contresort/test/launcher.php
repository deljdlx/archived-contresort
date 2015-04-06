<?php

namespace Contresort\Test;

class Launcher
{
	protected $results=array();

	
	public function __construct() {

	}
	
	
	
	public function runMethod($methodName) {
		ob_start();
		
		$memoryStart=memory_get_usage(true);
		$memoryPeakStart=memory_get_peak_usage(true);
		
		$startTime=microtime(true);
		
		$result=$this->$methodName();
		
		$memoryEnd=memory_get_usage(true)-$memoryStart;
		$memoryPeakEnd=memory_get_peak_usage(true)-$memoryPeakStart;
		
		
		$duration=microtime(true)-$startTime;
		$output=ob_get_clean();
		
		$testResults=array(
		
			'method'=>$methodName,
			'result'=>$result,
			'duration'=>$duration,
			'output'=>$output,
			'memory'=>$memoryEnd,
			'memoryPeak'=>$memoryPeakEnd
		);
		return $testResults;
		
	}
	
	
	public function getTests() {
		$reflection=new \ReflectionClass($this);
		$methods=$reflection->getMethods();
		
		$tests=array();
		
		foreach($methods as $method) {
			if($this->isTest($method)) {
				$tests[]=$method;
			}
		}
		return $tests;
	}
	
	protected function isTest($method) {
		if($method->getDeclaringClass()->name!=__CLASS__) {
			if($method->isPublic()) {
				return true;
			}
		}
		return false;
	}


	public function runAll() {
		
		$tests=$this->getTests();
		$results=array();
		
		foreach($tests as $method) {
	
			$results[]=$this->runMethod($method->name);
		}
		
		$this->results=$results;
		return $results;
	}
}



