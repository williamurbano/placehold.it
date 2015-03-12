<?php

namespace PlaceholdIt;

use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use PlaceholdIt\View\Helper\PlaceholdIt;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements ViewHelperProviderInterface, AutoloaderProviderInterface {

	public function onBootstrap() {
		echo 1; exit;
	}

	public function getAutoloaderConfig() {
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	public function getViewHelperConfig() {
		return array(
			'factories' => array(
				'placeholdIt' => function ($serviceManager) {
					return new PlaceholdIt();
				}
			)
		);
	}

}