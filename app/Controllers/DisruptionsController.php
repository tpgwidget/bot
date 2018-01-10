<?php

namespace App\Controllers;

use App\Models\Disruptions;
use App\Models\Subscriptions;

class DisruptionsController
{
    public static function updateDisruptions()
    {
        $new = Disruptions::getNew();
        
        Subscriptions::send($new);
        
        Disruptions::store($new);
    }
}
