<?php
declare(strict_types=1);

namespace Meraki\Composition\Test\Fixture;

use Meraki\Composition\Test\Fixture\Dependency;
use Meraki\Composition\Test\Fixture\SubDependencyA;

final class WithDefaultDependency
{
	public function __construct(
		public readonly Dependency $dep = new SubDependencyA()
	) {}
}
