<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class WithNullableDependency
{
	public function __construct(?NonExistentDependency $dependency) {}
}
