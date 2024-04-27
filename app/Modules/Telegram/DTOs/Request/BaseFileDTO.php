<?php

namespace App\Modules\Telegram\DTOs\Request;

use Illuminate\Http\UploadedFile;

abstract class BaseFileDTO extends BaseDTO
{
    protected array $container = [];

    public UploadedFile|string $file;

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->container[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->container[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->container[$offset]);
    }

    public function jsonSerialize(): array
    {
        return array_filter($this->container);
    }

    public function toArray(): array
    {
        return array_filter($this->container);
    }
}
