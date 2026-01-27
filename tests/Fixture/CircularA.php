<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class CircularA
{
	public function __construct(CircularB $b)
	{
	}
}
