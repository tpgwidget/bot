<?php
namespace TPGwidget\Bot\Controllers;

use TPGwidget\Bot\Models\Disruptions;
use TPGwidget\Bot\Models\Subscriptions;

class DisruptionsController
{
    /**
     * Fetch the new disruptions and send them to the subscriers
     */
    public static function updateDisruptions()
    {
        $new = Disruptions::getNew();

        Subscriptions::send($new);

        Disruptions::store($new);
    }
}
