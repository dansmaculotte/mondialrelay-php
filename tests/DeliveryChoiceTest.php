<?php

namespace DansMaCulotte\MondialRelay\Tests;

use DansMaCulotte\MondialRelay\DeliveryChoice;
use DansMaCulotte\MondialRelay\Exceptions\Exception;
use DansMaCulotte\MondialRelay\Resources\PickupPoint;

class DeliveryChoiceTest extends TestCase
{
    protected function stdStringMock($value)
    {
        $object = new \stdClass();
        $object->string = $value;

        return $object;
    }

    public function testFindPickupPoints()
    {
        $mondialRelayWSDL = $this->getMockFromWsdl(
            __DIR__.'/assets/mondialrelay.wsdl',
            'WSI4_PointRelais_Recherche'
        );

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

        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 0;
        $mockedPickupPointsResult->PointsRelais = new \stdClass();
        $mockedPickupPointsResult->PointsRelais->PointRelais_Details = [
            $mockedPickupPoint,
        ];

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $mondialRelayWSDL;

        $results = $delivery->findPickupPoints('Paris', '75001', 'FR');

        $this->assertContainsOnlyInstancesOf(PickupPoint::class, $results);
    }

    public function testFindPickupPointsWithError()
    {
        $mondialRelayWSDL = $this->getMockFromWsdl(
            __DIR__.'/assets/mondialrelay.wsdl',
            'WSI4_PointRelais_Recherche'
        );

        $mockedPickupPointsResult = new \stdClass();
        $mockedPickupPointsResult->STAT = 1;

        $mockedResult = new \stdClass();
        $mockedResult->WSI4_PointRelais_RechercheResult = $mockedPickupPointsResult;

        $mondialRelayWSDL
            ->expects($this->any())
            ->method('WSI4_PointRelais_Recherche')
            ->willReturn($mockedResult);

        $delivery = new DeliveryChoice($this->credentials);
        $delivery->soapClient = $mondialRelayWSDL;

        $this->expectExceptionObject(Exception::requestError(1));
        $delivery->findPickupPoints('Paris', '75001', 'FR');
    }
}
