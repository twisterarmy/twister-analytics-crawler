<?php

class CurlGeoPlugin extends Curl {

    public function getLocation($ip) {

        $this->prepare('json.gp?ip=' . $ip, 'GET');

        if ($response = $this->execute()) {

            switch (false) {
                case isset($response['geoplugin_city']):
                case isset($response['geoplugin_countryCode']):
                case isset($response['geoplugin_latitude']):
                case isset($response['geoplugin_longitude']):

                    return false;
            }

            return $response;
        }

        return false;
    }
}
