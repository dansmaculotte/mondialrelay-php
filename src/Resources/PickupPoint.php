<?php

namespace DansMaCulotte\MondialRelay\Resources;

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

    /** @var array */
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
            $this->$value = ($parameters->$key ?? null);
        }

        $this->openings = $this->parseOpenings(
            $parameters->Horaires_Lundi->string,
            $parameters->Horaires_Mardi->string,
            $parameters->Horaires_Mercredi->string,
            $parameters->Horaires_Jeudi->string,
            $parameters->Horaires_Vendredi->string,
            $parameters->Horaires_Samedi->string,
            $parameters->Horaires_Dimanche->string
        );
    }

    /**
     * @param array $monday
     * @param array $tuesday
     * @param array $wednesday
     * @param array $thursday
     * @param array $friday
     * @param array $saturday
     * @param array $sunday
     * @return array
     */
    private function parseOpenings(
        array $monday,
        array $tuesday,
        array $wednesday,
        array $thursday,
        array $friday,
        array $saturday,
        array $sunday
    ) {
        $weekDays = [
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday,
        ];

        foreach ($weekDays as $dayKey => $hours) {
            $day = [];
            $hoursCount = count($hours);
            for ($i = 0; $i < $hoursCount; $i+=2) {
                if (!empty($hours[$i]) && !empty($hours[$i + 1])) {
                    $day[] = implode(
                        '-',
                        [
                            implode(':', str_split($hours[$i], 2)),
                            implode(':', str_split($hours[$i + 1], 2))
                        ]
                    );
                }
            }
            $weekDays[$dayKey] = $day;
        }

        return $weekDays;
    }
}
