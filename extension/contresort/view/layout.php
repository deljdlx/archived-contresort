<?php
namespace Contresort\View;

class Layout
{
	
	protected $selectors=array();
	protected $templateBuffer;



	public function assign($selector, $value) {
		$this->selectors[$selector]=$value;
	}
	
	
	public function loadHTML($buffer) {
		$this->domEngine=new DOM\Element($buffer);
		$this->templateBuffer=$buffer;
		
		return $this;
	
	}
	
	
	public function render($templateBuffer=null) {
		if($templateBuffer) {
			$this->loadHTML($templateBuffer);
		}
		
		return $this->domEngine->render();
		
		
		
		
	}
}