<?php
namespace TPGwidget\Bot\Models;

use TPGwidget\Bot\Models\TPGOpenData;

/**
 * TPG disruptions model
 */
class Disruptions
{
    /**
     * Get the new disruptions
     * @return mixed[]
     */
    public static function getNew()
    {
        $stored = self::getStored();

        $current = TPGOpenData::fetch('Disruptions');

        // Return the current disruptions that are not stored
        return array_filter($current, function($disruption) use ($stored) {
            return (! in_array($disruption, $stored));
        });
    }

    /**
     * Get the disruptions stored in database
     * @return mixed[]
     */
    private static function getStored()
    {
        global $db;

        $req = $db->query('SELECT timestamp, place, nature, consequence, lineCode
        FROM disruptions
        '); // WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)

        $disruptions = $req->fetchAll(\PDO::FETCH_ASSOC);

        // Format the perturbation timestamp at the ISO format,
        // used by the TPG Open Data API
        $disruptions = array_map(function($disruption) {
            $disruption['timestamp'] = date('Y-m-d\TH:i:sO', strtotime($disruption['timestamp']));
            return $disruption;
        }, $disruptions);

        return $disruptions;
    }

    /**
     * Store disruptions in database
     * @param  mixed[] $disruptions
     */
    public static function store($disruptions)
    {
        global $db;

        $values = '';
        $bind = [];

        $i = 0;
        foreach ($disruptions as $disruption) {
            if ($i > 0) $values .= ',';
            $values .= '(?, ?, ?, ?, ?)';

            $bind[] = date('Y-m-d H:i:s', strtotime($disruption['timestamp']));
            $bind[] = $disruption['lineCode'];
            $bind[] = $disruption['place'] ?: null;
            $bind[] = $disruption['nature'];
            $bind[] = $disruption['consequence'];

            $i += 1;
        }

        $req = $db->prepare(
            'INSERT INTO
            disruptions(timestamp, lineCode, place, nature, consequence)
            VALUES '.$values
        );

        $req->execute($bind);
    }
}
