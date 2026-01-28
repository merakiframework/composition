<?php
declare(strict_types=1);

namespace Meraki\Composition\Test\Fixture;

final class SubDependencyA extends Dependency
{
	public readonly string $name;
	public function __construct()
	{
		$this->name = 'sub_dependency_a';
	}
}
