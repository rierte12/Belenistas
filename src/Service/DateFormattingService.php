<?php declare(strict_types=1);

namespace App\Service;

use DateTime;
use IntlDateFormatter;

class DateFormattingService {
    public static function convertToHumanString(DateTime $date): string
    {
        $formatter = new IntlDateFormatter(
            'es_ES',
            IntlDateFormatter::LONG,
            IntlDateFormatter::LONG,
            'Europe/Madrid'
        );

        return $formatter->formatObject($date, "eeee, d 'de' MMMM 'de' Y 'a las' H:mm", "es_ES");
    }

    public static function convertDefault(DateTime $date): string
    {

        return $date->format("Y-m-d H:i:s");
    }
}