<?php
declare(strict_types=1);

namespace App\Tests\Unit\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidIsbnException;
use PHPUnit\Framework\TestCase;
use App\Catalog\Domain\ValueObject\Isbn;

final class IsbnTest extends TestCase
{
    public function testIsbnWithInvalidDigits(): void {
        $this->expectException(InvalidIsbnException::class);

        Isbn::fromString('1234');
    }

    public function testIsbnWithHyphenDigits(): void {
       $isbn = Isbn::fromString('978-0-13-235088-4');
       $digits = $isbn->getDigits();

       $this->assertSame('9780132350884', $digits);
    }

    public function testIsbnWithSpacesDigits(): void {
        $isbn = Isbn::fromString('978 0 13 235088 4');
        $digits = $isbn->getDigits();

        $this->assertSame('9780132350884', $digits);
    }

    public function testIsbnWithCharacterDigits(): void {
        $this->expectException(InvalidIsbnException::class);

        Isbn::fromString('978013235088X');
    }

    public function testIsbnWithDotsDigits(): void {
        $this->expectException(InvalidIsbnException::class);

        Isbn::fromString('978.0.13.235088.4');
    }

    public function testIsbnValidPrefix(): void {
        $isbn = Isbn::fromString('9790132350884');
        $digits = $isbn->getDigits();

        $this->assertSame('9790132350884', $digits);
    }

    public function testIsbnInvalidPrefix(): void {
        $this->expectException(InvalidIsbnException::class);

        Isbn::fromString('9770132350884');
    }

    public function testIsbnWithValidDigits(): void {
        $isbn = Isbn::fromString('9780132350884');
        $digits = $isbn->getDigits();

        $this->assertSame('9780132350884', $digits);
    }
}

