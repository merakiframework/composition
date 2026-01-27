<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class CircularB
{
	public function __construct(CircularC $c)
	{
	}
}
