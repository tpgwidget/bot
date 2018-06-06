<?php
namespace TPGwidget\Bot\Models;

/**
 * Allows us to use the TPG API
 */
class TPGOpenData
{
    /**
    * Fetch data from the TPG API
    *
    * @param  string   $name    Name of the request (example: NextDepartures)
    * @param  string[] $params  Parameters for the request
    * @return string[] TPG API response
    */
    public static function fetch($name, $params = [])
    {
        $params['key'] = getenv('TPG_API_KEY');

        $client = new \GuzzleHttp\Client();
        $response = $client->get('http://prod.ivtr-od.tpg.ch/v1/Get'.$name.'.json', [
            'query' => $params,
        ]);

        if ($response->getStatusCode() != "200")
            throw new Exception("", 500);

        return json_decode($response->getBody(), true)[strtolower($name)];
    }
}
