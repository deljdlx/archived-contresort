<?php


define('CALL_FILEPATH', getcwd());
chdir(__DIR__);
define('APPLICATION_FILEPATH', __DIR__);
define('CONTRESORT_FILEPATH', realpath(__DIR__.'/../../source'));

require(CONTRESORT_FILEPATH.'/contresort.php');


$extension=new \Contresort\Extension('Contresort\View', '../../extension/contresort/view');


class Test extends \Contresort\View\Widget
{

}
