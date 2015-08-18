<?php
namespace Contresort\Route;



class Router
{


    protected $routingRules=array();


    public function __construct() {

    }


    public function get($validator, $action=null) {
        $routeDescriptor=$this->createRoute('get', $validator);
        if($action) {
            $this->addAction($action);
        }
        return $routeDescriptor;
    }

    public function post($validator, $action=null) {
        $routeDescriptor=$this->createRoute('post', $validator);
        if($action) {
            $this->addAction($action);
        }
        return $routeDescriptor;
    }

    public function cli($validator, $action=null) {
        $routeDescriptor=$this->createRoute('cli', $validator);
        if($action) {
            $this->addAction($action);
        }
        return $routeDescriptor;
    }





    public function createRouteDescriptor($method, $validator) {
        $descriptor = new Descriptor(
            new Rule($method, $validator)
        );
        return $descriptor;
    }

    public function createRoute($method, $validator) {
        $descriptor = $this->createRouteDescriptor($method, $validator);
        $this->addRouteDescriptor($descriptor);
        return $descriptor;
    }


    public function addRouteDescriptor($descriptor) {
        $this->routingRules[] = $descriptor;
        return $this;
    }



}
