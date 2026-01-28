<?php
declare(strict_types=1);

namespace Meraki\Integration\Test\Fixture;

use Meraki\Integration\Test\Fixture\Dependency;
use Meraki\Integration\Test\Fixture\SubDependencyA;

final class WithDefaultDependency
{
	public function __construct(
		public readonly Dependency $dep = new SubDependencyA()
	) {}
}
