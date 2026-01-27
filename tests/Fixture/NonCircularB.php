<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class NonCircularB
{
	public function __construct(NonCircularC $c)
	{
	}
}
