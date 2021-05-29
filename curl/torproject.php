<?php

class CurlTorProject extends Curl {

    public function getExitNodes() {

        $this->prepare('torbulkexitlist', 'GET');

        if ($response = $this->execute(false)) {

            $list = explode("\n", $response);

            if (count($list)) {

                $sanitized = [];
                foreach ($list as $ip) {

                    $ip = $this->sanitize($ip);

                    if ($ip) {
                        $sanitized[] = $ip;
                    }

                }

                return $sanitized;
            }
        }

        return false;
    }
}
