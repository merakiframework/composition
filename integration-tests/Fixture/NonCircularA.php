<?php
declare(strict_types=1);

namespace Meraki\Integration\Test\Fixture;

final class NonCircularA
{
	public function __construct(NonCircularB $b)
	{
	}
}
