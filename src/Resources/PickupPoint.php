<?php

namespace DansMaCulotte\MondialRelay\Resources;

use DansMaCulotte\MondialRelay\Helpers\OpeningHoursParser;

class PickupPoint
{
    /** @var string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $nameAdditional;

    /** @var string */
    public $address;

    /** @var string */
    public $addressOptional;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $city;

    /** @var string */
    public $countryCode;

    /** @var string */
    public $latitude;

    /** @var string */
    public $longitude;

    /** @var string */
    public $activityTypeCode;

    /** @var string */
    public $distance;

    /** @var string */
    public $locationHint;

    /** @var string */
    public $locationHintOptional;

    /** @var \Spatie\OpeningHours\OpeningHours */
    public $openings;

    /** @var string */
    public $holidays;

    /** @var string */
    public $startHolidays;

    /** @var string */
    public $endHolidays;

    /** @var string */
    public $mapUrl;

    /** @var string */
    public $photoUrl;


    public function __construct($parameters)
    {
        $parametersMap = [
            'Num' => 'id',
            'LgAdr1' => 'name',
            'LgAdr2' => 'nameAdditional',
            'LgAdr3' => 'address',
            'LgAdr4' => 'addressOptional',
            'CP' => 'postalCode',
            'Ville' => 'city',
            'Pays' => 'countryCode',
            'Latitude' => 'latitude',
            'Longitude' => 'longitude',
            'TypeActivite' => 'activityTypeCode',
            'Distance' => 'distance',
            'Localisation1' => 'locationHint',
            'Localisation2' => 'locationHintOptional',
            'Debut' => 'startHolidays',
            'Fin' => 'endHolidays',
            'URL_Plan' => 'mapUrl',
            'URL_Photo' => 'photoUrl',
        ];

        foreach ($parametersMap as $key => $value) {
            $this->$value = (isset($parameters->$key) ? $parameters->$key : null);
        }

        $this->openings = OpeningHoursParser::parse($parameters);
    }
}
