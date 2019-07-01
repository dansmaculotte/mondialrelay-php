<?php

namespace DansMaCulotte\MondialRelay\Resources;


class PickupPoint
{
    public $id;
    public $name;
    public $nameAdditional;
    public $address;
    public $addressOptional;
    public $postalCode;
    public $city;
    public $countryCode;
    public $latitude;
    public $longitude;
    public $activityTypeCode;
    public $distance;
    public $locationHint;
    public $locationHintOptional;
    public $openings;
    public $holidays;
    public $startHolidays;
    public $endHolidays;
    public $mapUrl;
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