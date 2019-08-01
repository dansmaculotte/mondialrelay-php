<?php

namespace DansMaCulotte\MondialRelay\Helpers;

use Spatie\OpeningHours\OpeningHours;
use stdClass;

class OpeningHoursParser
{
    /**
     * Parse the SOAP result of a relay point and returns an array of OpeningHours
     *
     * @param stdClass $relayPoint
     * @return OpeningHours
     */
    public static function parse(stdClass $relayPoint)
    {
        $businessHours = [];

        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];

        foreach ($days as $day => $dayFr) {
            $businessHours[$day] =  OpeningHoursParser::formatOpeningHoursDay($relayPoint->{'Horaires_' . $dayFr}->string);
        }

        $openingHours = OpeningHours::create($businessHours);

        return $openingHours;
    }


    /**
     * @param array $hours Array of string of hours e.g. [1030, 1400, 1500, 2000]
     * @return array       Array of formatted hours e.g. [10:30-14:00, 15:00-20:00]
     */
    private static function formatOpeningHoursDay(array $hours)
    {
        $validOpenings = [];
        if (!empty($hours[0]) && !empty($hours[1])) {
            array_push($validOpenings, OpeningHoursParser::formatRangeTime($hours[0], $hours[1]));
        }

        if (!empty($hours[2]) && !empty($hours[3])) {
            array_push($validOpenings, OpeningHoursParser::formatRangeTime($hours[2], $hours[3]));
        }

        return $validOpenings;
    }


    /**
     * @param string $startTime Date time e.g. 1030
     * @param string $endTime   Date time e.g. 1400
     * @return string           Formatted range time e.g. 10:30-14:00
     */
    private static function formatRangeTime(string $startTime, string $endTime)
    {
        return implode('-', [OpeningHoursParser::formatTime($startTime), OpeningHoursParser::formatTime($endTime)]);
    }


    /**
     * @param string $hours  Date time e.g. 1030
     * @return string        Formatted date time e.g. 10:30
     */
    private static function formatTime($hours)
    {
        return implode(':', str_split($hours, 2));
    }
}
