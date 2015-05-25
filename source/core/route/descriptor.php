<?php

namespace Contresort\Route;


class Descriptor
{
	protected $name;
	protected $rule;

	protected $preActions=array();
	protected $actions=array();
	protected $postActions=array();


	protected $selected=false;

	protected $builder;


	protected $expires=null;
	protected $maxAge=null;
	protected $noStore=true;
	protected $noCache=true;
	protected $cacheVisibility='private';
	protected $eTag=null;



	/**
	 * @param \Contresort\Route\Rule $rule
	 */

	public function __construct($rule) {
		$this->rule=$rule;
	}


	public function selected($value=null) {
		if($value!==null) {
			$this->selected=$value;
			return $this;
		}
		else {
			return $this->selected;
		}
	}


	public function name($name=null) {
		if($name!==null) {
			$this->name = $name;
			return $this;
		}
		else {
			return $this->name;
		}
	}




	public function builder($builder) {
		$this->builder=$builder;
		return $this;
	}

	public function buildURL($parameters=array()) {
		if(is_callable($this->builder)) {
			return call_user_func_array(array($this->builder, '__invoke'), $parameters);
		}
		else if(is_string($this->builder)) {
			return $this->builder;
		}
		else {
			return false;
		}
	}



	public function addPreAction($action) {
		$this->preActions[]=$action;
		return $this;
	}

	public function addAction($action) {
		$this->actions[]=$action;
		return $this;
	}

	public function addPostAction($action) {
		$this->postActions[]=$action;
		return $this;
	}


	/**
	 * @param \Contresort\Application $application
	 * @return $this
	 */
	public function isValid($application) {
		if($this->rule->validate($application)) {
			return true;
		}
		else {
			return false;
		}
	}


	/**
	 * @param \Contresort\Application $application
	 * @return $this
	 */
	public function run($application) {
		$parameters=$this->rule->getParameters();
		foreach ($this->actions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), $parameters);

			if($result===true) {
				return $this;
			}
		}

		return false;
	}

	/**
	 * @param \Contresort\Application $application
	 * @return $this
	 */
	public function runAfter($application) {
		$parameters=$this->rule->getParameters();
		foreach ($this->postActions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), $parameters);
			if(!$result) {
				break;
			}
		}
		return $this;
	}

	/**
	 * @param \Contresort\Application $application
	 * @return $this
	 */
	public function runBefore($application) {

		foreach ($this->preActions as $action) {
			$closure=$action->bindTo($application, $application);
			$result=call_user_func_array(array($closure, '__invoke'), array());
			if(!$result) {
				break;
			}
		}
		return $this;
	}


	//cache control=========================================

	public function noStore($noStore=null) {
		if($noStore===null) {
			return $this->noStore;
		}

		$this->noStore=$noStore;
		return $this;
	}

	public function noCache($noCache=null) {
		if($noCache===null) {
			return $this->noCache;
		}

		if($noCache===false) {
			$this->noStore(false);
		}


		$this->noCache=$noCache;
		return $this;
	}

	public function expires($gmtDate=null) {
		if($gmtDate===null) {
			return $this->expires;
		}

		if($gmtDate) {
			$this->noStore(false);
		}


		$this->expires=$gmtDate;
		return $this;
	}

	public function cacheVisibility($visibility=null) {
		if($visibility===null) {
			return $this->cacheVisibility;
		}

		$this->cacheVisibility=$visibility;
		return $this;
	}

	public function maxAge($second=null) {
		if($second===null) {
			return $this->maxAge;
		}

		if($second) {
			$this->noStore(false);
		}

		$this->maxAge=$second;
		return $this;
	}

	public function eTag($eTag=null) {
		if($eTag===null) {
			return $this->eTag;
		}

		if($eTag) {
			$this->noStore(false);
		}

		$this->eTag=$eTag;
		return $this;
	}








}