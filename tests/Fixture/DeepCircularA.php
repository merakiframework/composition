<?php
declare(strict_types=1);

namespace Meraki\Container\Test\Fixture;

final class DeepCircularA
{
	public function __construct(DeepCircularB $b)
	{
	}
}
