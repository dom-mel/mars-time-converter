<?php

namespace App\Service;

use DateTime;
use RuntimeException;

class ConverterService
{

    private function calcMillis(DateTime $date): float
    {
        return (int)$date->format('U') * 1000 + (int)$date->format('v');
    }

    private function millisToJulianDate(float $millis): float
    {
        return 2440587.5 + ($millis / 8.64e7);
    }

    private function julianDateTerrestrialTime(float $julianDate, float $leapSeconds): float
    {
        return $julianDate + ($leapSeconds / 86400);
    }

    private function leapSecondsAt(DateTime $date): float
    {
        $numberOfLeapSeconds = null;
        foreach ($this->readLeapSeconds() as $leapSecondTimestamp => $leapSeconds) {
            if ($leapSecondTimestamp > $date->getTimestamp()) {
                break;
            }
            $numberOfLeapSeconds = $leapSeconds;
        }
        if ($numberOfLeapSeconds === null) {
            throw new RuntimeException('leap-seconds.list missing or malformed - check Readme.md');
        }
        return $numberOfLeapSeconds;
    }

    private function readLeapSeconds(): array
    {
        $content = file(__DIR__ . '/../../leap-seconds.list', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($content === false) {
            throw new RuntimeException('leap-seconds.list missing or malformed - check Readme.md');
        }
        $leapSeconds = [];
        foreach ($content as $line) {
            if (!ctype_digit(mb_substr($line, 0, 1))) {
                continue;
            }
            $parts = explode("\t", $line);
            $ts = mktime(0, 0, 0, 1, 1, 1900);
            if ($ts === false) {
                throw new RuntimeException('leap-seconds.list malformed');
            }
            $ts += (int)$parts[0];

            $leapSeconds[$ts] = (int)$parts[1];
        }
        ksort($leapSeconds);
        return $leapSeconds;
    }

    public function getMarsSolDate(DateTime $date): float
    {
        if ($date < new DateTime('1972-01-01T00:00:00Z')) {
            throw new \InvalidArgumentException('Date MUST be after 1972-01-01');
        }
        $julianDateTerrestrialTime = $this->julianDateTerrestrialTime($this->millisToJulianDate($this->calcMillis($date)), $this->leapSecondsAt($date));
        return ($julianDateTerrestrialTime - 2405522.0028779) / 1.0274912517;
    }

    public function getCoordinatedMarsTime(DateTime $date): string
    {
        $marsSoleDateSecond = new DateTime();
        $marsSoleDateSecond->setTimestamp($this->getMarsSolDate($date) * 86400 + $this->leapSecondsAt($date));
        $marsSoleDateSecond->setTimezone(new \DateTimeZone('UTC'));
        return $marsSoleDateSecond->format('H:i:s');
    }
}
