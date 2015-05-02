<?php


chdir(__DIR__);

require(realpath(__DIR__.'/../../source/contresort.php'));

$extension=new \Contresort\Extension('Contresort\View', '../../extension/contresort/view');


class Test extends \Contresort\View\Widget
{

}
