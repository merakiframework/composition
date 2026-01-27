<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

use Meraki\Container\Test\Fixture\Dependency;
use Meraki\Container\Test\Fixture\SubDependencyA;

final class WithDefaultDependency
{
	public function __construct(
		public readonly Dependency $dep = new SubDependencyA()
	) {}
}
