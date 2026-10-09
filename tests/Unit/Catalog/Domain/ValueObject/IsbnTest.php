<?php
declare(strict_types=1);

namespace App\Tests\Unit\Catalog\Domain\ValueObject;

use App\Catalog\Domain\Exception\InvalidIsbnException;
use PHPUnit\Framework\TestCase;
use App\Catalog\Domain\ValueObject\Isbn;

final class IsbnTest extends TestCase
{
    public function testIsbnWithInvalidDigits(): void
    {
        $this->expectException(InvalidIsbnException::class);

        new Isbn('1234');
    }

    public function testIsbnWithValidDigits(): void {
        $isbn = new Isbn('9780132350884');
        $digits = $isbn->getDigits();

        $this->assertSame('9780132350884', $digits);
    }
}
