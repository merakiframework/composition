<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class NonCircularA
{
	public function __construct(NonCircularB $b)
	{
	}
}
