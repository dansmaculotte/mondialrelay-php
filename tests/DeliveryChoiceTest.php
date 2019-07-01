<?php

use DansMaCulotte\MondialRelay\DeliveryChoice;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.php';

class DeliveryChoiceTest extends TestCase
{
    public function testDeliveryChoice()
    {
        $delivery = new DeliveryChoice(
            array(
                'site_id' => MONDIAL_RELAY_SITE_ID,
                'site_key' => MONDIAL_RELAY_SITE_KEY,
            )
        );

        $result = $delivery->findPickupPoints('Paris', '75001', 'FR', 4);

        print_r($result);
    }
}