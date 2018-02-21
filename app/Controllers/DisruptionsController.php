<?php

namespace App\Controllers;

use App\Models\Disruptions;
use App\Models\Subscriptions;

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
