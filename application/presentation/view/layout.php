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
			'urlindex'=>$application->buildURL('index'),
			'urlapropos'=>$application->buildURL('apropos'),
			'urlmanifeste'=>$application->buildURL('manifeste'),
			'urldemarrage'=>$application->buildURL('demarrage'),
			'urlextension'=>$application->buildURL('extension'),
			'urldocumentation'=>$application->buildURL('documentation'),
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
