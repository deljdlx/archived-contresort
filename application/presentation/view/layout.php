<?php


namespace Contresort\Presentation\View;

class Layout extends \Contresort\Template
{

	protected $application;
	protected $page;

	public function setApplication($application) {
		$this->application=$application;
	}

	public function setPage($page) {
		$this->page=$page;
	}

	protected function getNavigationBar($application) {

		$anchors=$this->getMenuLinks($application);

		$anchors['selectedItem']=$application->getSelectedRoute()->name();

		$buffer=obinclude('template/block/navigationbar.php', $anchors);
		return $buffer;
	}

	protected function getMenuLinks($application) {
		$anchors=array(
			'urlindex'=>$application->getURL('index'),
			'urlapropos'=>$application->getURL('apropos'),
			'urlmanifeste'=>$application->getURL('manifeste'),
			'urldemarrage'=>$application->getURL('demarrage'),
			'urlextension'=>$application->getURL('extension'),
			'urldocumentation'=>$application->getURL('documentation'),
		);
		return $anchors;
	}

	public function render($layout, $variables=array()) {
		$this->assign(array(
			'navigationBar'=>$this->getNavigationBar($this->application),
			'content'=>obinclude('template/page/'.$this->page, $this->getMenuLinks($this->application)),
			'footer'=>obinclude('template/block/footer.php'),
		));
		return parent::render($layout, $variables);
	}

}
