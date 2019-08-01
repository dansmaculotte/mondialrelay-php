<?php

namespace DansMaCulotte\MondialRelay;

use DansMaCulotte\MondialRelay\Exceptions\Exception;
use DansMaCulotte\MondialRelay\Resources\PickupPoint;

/**
 * Implementation of Delivery Choice Web Service
 * https://www.mondialrelay.fr/media/108937/Solution-Web-Service-V5.6.pdf
 */
class DeliveryChoice extends MondialRelay
{
    /** @var string */
    const SERVICE_URL = 'https://api.mondialrelay.com/Web_Services.asmx?WSDL';

    /**
     * DeliveryChoice constructor.
     * @param array $credentials   Contains site id and site key
     * @param array $options       Additional parameters to submit to the web services
     * @throws \Exception
     */
    public function __construct(array $credentials, array $options = [])
    {
        parent::__construct($credentials, self::SERVICE_URL, $options);
    }


    /**
     * @param string $city                City name
     * @param string $zipCode             Zip code
     * @param string $country             ISO 3166 country code
     * @param int $nbResults              Number of results
     * @param string|null $code           Pickup point code
     * @param string|null $latitude       Latitude
     * @param string|null $longitude      Longitude
     * @param string|null $weight         Weight in grams
     * @param string|null $collectType    Delivery or collect type
     * @param string|null $sendingDelay   Delay before sending
     * @param int|null $searchRadius      Radius from the origin point
     * @return array
     * @throws \Exception
     */
    public function findPickupPoints(
        string $city,
        string $zipCode,
        string $country,
        int $nbResults = null,
        string $code = null,
        string $latitude = null,
        string $longitude = null,
        string $weight = null,
        string $collectType = null,
        string $sendingDelay = null,
        int $searchRadius = null
    ) {
        $options = [
                'Ville' => $city,
                'CP' => $zipCode,
                'Pays' => $country,
                'NombreResultats' => $nbResults,
                'NumPointRelais' => $code,
                'Latitude' => $latitude,
                'Longitude' => $longitude,
                'Action' => $collectType,
                'Poids' => $weight,
                'DalayEnvoie' => $sendingDelay,
                'RayonRecherche' => $searchRadius,
        ];

        $result = $this->soapExec(
            'WSI4_PointRelais_Recherche',
            $options
        );

        $errorCode = $result->WSI4_PointRelais_RechercheResult->STAT;
        if ($errorCode != 0) {
            throw Exception::requestError($errorCode);
        }

        $result = $result->WSI4_PointRelais_RechercheResult->PointsRelais->PointRelais_Details;

        $pickupPoints = array_map(
            function ($pickupPoint) {
                return new PickupPoint($pickupPoint);
            },
            $result
        );

        return $pickupPoints;
    }
}
