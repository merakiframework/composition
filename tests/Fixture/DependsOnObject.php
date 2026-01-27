<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class DependsOnObject
{
	public function __construct(Dependency $dependency) {}
}
