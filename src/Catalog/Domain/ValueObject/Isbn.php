<?php

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidIsbnException;

class Isbn
{
    public function __construct(private readonly string $digits)
    {
        if(strlen($this->digits) !== 13){
            throw new InvalidIsbnException(
                sprintf('%s is an invalid Isbn', $this->digits)
            );
        }
    }

    public function getDigits(): string {
        return $this->digits;
    }
}
