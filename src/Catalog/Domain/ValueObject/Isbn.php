<?php

namespace App\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidIsbnException;

class Isbn
{
    private const array ALLOWED_PREFIXES = ['978', '979'];

    private function __construct(private readonly string $digits)
    {
        if(strlen($this->digits) !== 13){
            throw new InvalidIsbnException(
                sprintf('%s is an invalid Isbn', $this->digits)
            );
        }

        if(!ctype_digit($this->digits)){
            throw new InvalidIsbnException(
                sprintf('%s is an invalid Isbn', $this->digits)
            );
        }

        if(!in_array(substr($this->digits, 0, 3), self::ALLOWED_PREFIXES, true)) {
            throw new InvalidIsbnException(
                sprintf('%s is an invalid Isbn', $this->digits)
            );
        }
    }

    public static function fromString(string $value): self {
        $value = preg_replace('/[-\s]/', '', $value);

        return new self($value);
    }

    public function getDigits(): string {
        return $this->digits;
    }
}
