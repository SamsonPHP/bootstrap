<?php
namespace samsonphp\main;

use samson\core\CompressableExternalModule;

/**
 * Module controller
 * @package samsonphp\main
 */
class Controller extends CompressableExternalModule
{
    /** @var string Identifier */
	protected $id = 'main';

    /** Universal main controller action */
    function __handler()
    {
        $this->view('index')->title('Main page');
    }
}
