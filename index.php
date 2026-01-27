<?php
require_once __DIR__ . '/vendor/autoload.php';

use Meraki\Container\Config;
use Meraki\Container\Container;
use Meraki\Container\Exception\CircularReferenceFound;
use Meraki\Container\Test\Fixture;

$container = new Container(new Config());

// First resolution: circular dependency (expected to fail)
try {
	$container->get(Fixture\CircularA::class);
} catch (CircularReferenceFound $e) {
}

// Second resolution: must succeed and not be polluted by previous attempt
$instance = $container->get(Fixture\NonCircularA::class);

var_dump($container->resolutionPath);
