<?php
namespace Contresort;


class Template
{

	protected $variables=array();

	public function assign($variable, $value=null) {
		if(is_array($variable)) {
			$this->variables=array_merge($this->variables, $variable);
		}
		elseif(is_string($variable)) {
			$this->variables[$variable]=$value;
		}
		return $this;
	}


	public function render($template, $variables=array()) {
		if(!empty($variables)) {
			$this->assign($variables);
		}
		return obinclude($template, $this->variables);
	}


}