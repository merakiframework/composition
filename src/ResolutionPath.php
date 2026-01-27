<?php
declare(strict_types=1);

namespace Meraki\Container;

use Meraki\Container\Exception\CircularReferenceFound;
use IteratorAggregate;
use Countable;


final readonly class ResolutionPath implements IteratorAggregate, Countable
{
	public function __construct(private array $path = [])
	{
	}

	public function push(string $id): self
	{
		if ($this->contains($id)) {
			throw new CircularReferenceFound([...$this->path, $id]);
		}

		return new self([...$this->path, $id]);
	}

	public function reset(): self
	{
		return new self([]);
	}

	public function pop(): self
	{
		return new self(array_slice($this->path, 0, -1));
	}

	public function contains(string $id): bool
	{
		return in_array($id, $this->path, true);
	}

	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->path);
	}

	public function count(): int
	{
		return count($this->path);
	}
}
