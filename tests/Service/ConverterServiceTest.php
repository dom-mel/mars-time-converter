<?php

namespace App\Tests\Service;

use App\Service\ConverterService;
use DateTime;
use PHPUnit\Framework\TestCase;

class ConverterServiceTest extends TestCase
{

    private $converter;

    protected function setUp(): void
    {
        $this->converter = new ConverterService();
    }

    /**
     * @dataProvider provider
     * @param DateTime $date
     * @param float $msd
     * @param string $mtc
     */
    public function testGetMarsSolDate(DateTime $date, float $msd, string $mtc): void
    {
        $this->assertEquals($msd, round($this->converter->getMarsSolDate($date), 2));
        $this->assertEquals($mtc, $this->converter->getCoordinatedMarsTime($date));
    }

    final public function provider(): array
    {
        return [
            [
                new DateTime('2000-01-06T00:00:00Z'),
                44796.00,
                '23:59:39',
            ],
            [
                new DateTime('2004-01-03T13:46:31Z'),
                46215.55,
                '13:09:56',
            ],
            [
                new DateTime('2020-01-06T00:00:00Z'),
                51905.55,
                '13:11:13',
            ],
            [
                new DateTime('2154-01-01T00:00:00Z'),
                99534.18,
                '04:17:30',
            ],
        ];
    }

    public function testDateToEarly(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->converter->getMarsSolDate(new DateTime('1900-01-01T00:00:00Z'));
    }
}