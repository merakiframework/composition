<?php
declare(strict_types=1);

namespace Meraki\Integration\Test\Fixture;

final class CircularA
{
	public function __construct(CircularB $b)
	{
	}
}
