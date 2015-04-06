<?php


namespace Contresort\Test;



class Renderer
{

	protected $results;


	public function __construct($results) {
		$this->results=$results;
	}



	public function toHTML() {
		$buffer='';
		foreach($this->results as $result) {
			$success='failure';
		
			if($result['result']) {
				$success='valid';
			}
			$buffer.='<div class="'.$success.'">';
				$buffer.='<div class="testBody"><pre>';
				$buffer.=print_r($result['output'], true);
				$buffer.='</pre></div>';
				$buffer.='<div class="testDuration">'.$result['duration'].'</div>';
			$buffer.='</div>';
		}
		return $buffer;
	}

}

