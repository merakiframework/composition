<?php
declare(strict_types=1);

namespace Meraki\Composition\Test\Fixture;

final class WithNullableDependency
{
	public function __construct(?NonExistentDependency $dependency) {}
}
