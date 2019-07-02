<?php

use DansMaCulotte\MondialRelay\DeliveryChoice;
use DansMaCulotte\MondialRelay\Resources\PickupPoint;
use PHPUnit\Framework\TestCase;

require_once 'Credentials.php';

class DeliveryChoiceTest extends TestCase
{
    public function test_find_pick_up_points()
    {
        $delivery = new DeliveryChoice(
            array(
                'site_id' => MONDIAL_RELAY_SITE_ID,
                'site_key' => MONDIAL_RELAY_SITE_KEY,
            )
        );

        $result = $delivery->findPickupPoints('Paris', '75001', 'FR');

        foreach ($result as $point) {
            $this->assertInstanceOf(PickupPoint::class, $point);
        }

    }
}