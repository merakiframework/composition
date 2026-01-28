<?php
declare(strict_types=1);

namespace Meraki\Composition\Test\Fixture;

final class DependsOnObject
{
	public function __construct(Dependency $dependency) {}
}
