<?php
namespace Popov\ZfcEntity\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\Exception;

use Popov\ZfcEntity\Controller\Plugin\ModuleHelper as ModulePlugin;

class ModuleHelper extends AbstractHelper {

	/**
	 * @var ModuleHelper
	 */
	protected $modulePlugin;

	/**
	 * @param ModuleHelper $modulePlugin
	 */
	public function __construct(ModuleHelper $modulePlugin) {
		$this->modulePlugin = $modulePlugin;
	}

	public function __invoke() {
        $params = func_get_args();
        return call_user_func_array($this->modulePlugin, $params);
	}
}