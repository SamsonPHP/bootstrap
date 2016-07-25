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
    public function __handler()
    {
        $this->html((new \view\main\IndexView())->output())->title('Main page');
    }
}
