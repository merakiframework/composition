<?php
declare(strict_types=1);

namespace Meraki\Composition\Test\Fixture;

final class WithDefaultValue
{
	public function __construct(
		public string $name = 'default'
	) {}
}
