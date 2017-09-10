<?php
namespace Popov\ZfcEntity\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\Exception;

use Popov\ZfcEntity\Controller\Plugin\ModulePlugin as ModulePlugin;

class ModuleHelper extends AbstractHelper {

	/**
	 * @var ModulePlugin
	 */
	protected $modulePlugin;

	/**
	 * @param ModulePlugin $modulePlugin
	 */
	public function __construct(ModulePlugin $modulePlugin) {
		$this->modulePlugin = $modulePlugin;
	}

	public function __invoke() {
        $params = func_get_args();
        return call_user_func_array($this->modulePlugin, $params);
	}
}