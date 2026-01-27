<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class DeepCircularD
{
	public function __construct(DeepCircularB $b)
	{
	}
}
