<?php
declare(strict_types=1);

namespace Meraki\Integration\Test\Fixture;

final class WithDefaultValue
{
	public function __construct(
		public string $name = 'default'
	) {}
}
