<?php

namespace DansMaCulotte\MondialRelay\Tests;

use DansMaCulotte\MondialRelay\DeliveryChoice;
use DansMaCulotte\MondialRelay\Exceptions\Exception;
use DansMaCulotte\MondialRelay\Resources\PickupPoint;

class DeliveryChoiceTest extends TestCase
{
    protected $mondialRelayWSDL;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->mondialRelayWSDL = $this->getMockFromWsdl(
            __DIR__.'/assets/mondialrelay.wsdl',
            'WSI4_PointRelais_Recherche'
        );
    }

    protected function stdStringMock($value)
    {
        $object = new \stdClass();
        $object->string = $value;

        return $object;
    }

    protected function pickupPointMock()
    {
        $mockedPickupPoint = new \stdClass();
        $mockedPickupPoint->Num = '062049';
        $mockedPickupPoint->Lgdr1 = 'L\'UNIVERS DU MOBILE';
        $mockedPickupPoint->Lgdr2 = null;
        $mockedPickupPoint->Lgdr3 = '1 RUE DES PROUVAIRES';
        $mockedPickupPoint->Lgdr4 = null;
        $mockedPickupPoint->CP = '75001';
        $mockedPickupPoint->Ville = 'PARIS';
        $mockedPickupPoint->Pays = 'FR';
        $mockedPickupPoint->Latitude = '48,8614575';
        $mockedPickupPoint->Longitude = '2,3439127';
        $mockedPickupPoint->TypeActivite = '';
        $mockedPickupPoint->Distance = '1000';
        $mockedPickupPoint->Localisation1 = 'METRO LOUVRE-RIVOLI ';
        $mockedPickupPoint->Localisation2 = null;
        $mockedPickupPoint->Information = '';
        $mockedPickupPoint->Horaires_Lundi = $this->stdStringMock(['1030', '1400', '1500', '2000']);
        $mockedPickupPoint->Horaires_Mardi = $this->stdStringMock(['1030', '1400', '1500', '2000']);
        $mockedPickupPoint->Horaires_Mercredi = $this->stdStringMock(['1030', '1400', '1500', '2000']);
        $mockedPickupPoint->Horaires_Jeudi = $this->stdStringMock(['1030', '1400', '1500', '2000']);
        $mockedPickupPoint->Horaires_Vendredi = $this->stdStringMock(['1030', '1300', '1500', '2000']);
        $mockedPickupPoint->Horaires_Samedi = $this->stdStringMock(['1030', '1400', '1500', '2000']);
        $mockedPickupPoint->Horaires_Dimanche = $this->stdStringMock(['1400', '1800', '', '']);

        return $mockedPickupPoint;
    }

    public function testFindPickupPoints()
    {
        $mockedPickupPoint = $this->pickupPointMock();

        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 0;
        $mockedPickupPointsResult->PointsRelais = new \stdClass();
        $mockedPickupPointsResult->PointsRelais->PointRelais_Details = [
            $mockedPickupPoint,
        ];

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $this->mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $this->mondialRelayWSDL;

        $results = $delivery->findPickupPoints('FR', '75001', 'FR');

        $this->assertContainsOnlyInstancesOf(PickupPoint::class, $results);
    }

    public function testFindPickupPointsWithError()
    {
        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 1;

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $this->mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $this->mondialRelayWSDL;

        $this->expectExceptionObject(Exception::requestError(1));
        $delivery->findPickupPoints('Paris', '75001', 'FR');
    }

    public function testFindPickupPointsByCode()
    {
        $mockedPickupPoint = $this->pickupPointMock();

        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 0;
        $mockedPickupPointsResult->PointsRelais = new \stdClass();
        $mockedPickupPointsResult->PointsRelais->PointRelais_Details = [
            $mockedPickupPoint,
        ];

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $this->mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $this->mondialRelayWSDL;

        $result = $delivery->findPickupPointByCode('FR', '062049');

        $this->assertInstanceOf(PickupPoint::class, $result);
    }

    public function testFindPickupPointsByCodeWithError()
    {
        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 0;
        $mockedPickupPointsResult->PointsRelais = new \stdClass();
        $mockedPickupPointsResult->PointsRelais->PointRelais_Details = [];

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $this->mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $this->mondialRelayWSDL;

        $this->expectExceptionObject(Exception::noPickupPoint('062049'));
        $delivery->findPickupPointByCode('FR', '062049');
    }
}
