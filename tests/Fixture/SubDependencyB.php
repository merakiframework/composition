<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class SubDependencyB extends Dependency
{
	public readonly string $name;
	public function __construct()
	{
		$this->name = 'sub_dependency_b';
	}
}
