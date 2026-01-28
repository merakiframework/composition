<?php
declare(strict_types=1);

namespace Meraki\Integration\Test\Fixture;

final class CircularC
{
	public function __construct(CircularA $a)
	{
	}
}
