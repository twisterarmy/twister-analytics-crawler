<?php

class Curl {

    private $_curl;
    private $_protocol;
    private $_host;
    private $_port;

    public function __construct($protocol, $host, $port, $username = false, $password = false) {

        $this->_protocol  = $protocol;
        $this->_host      = $host;
        $this->_port      = $port;

        $this->_curl      = curl_init();


        if ($username && $password) {
            $headers = [
                'Content-Type: application/json',
                sprintf('Authorization: Basic %s', base64_encode($username . ':' . $password)),
            ];
        } else {
            $headers = [
                'Content-Type: application/json',
            ];
        }

        curl_setopt_array($this->_curl, [CURLOPT_RETURNTRANSFER => true,
                                         CURLOPT_FOLLOWLOCATION => true,
                                         CURLOPT_FRESH_CONNECT  => true,
                                         //CURLOPT_VERBOSE        => true,
                                         CURLOPT_HTTPHEADER     => $headers,
                                        ]);
    }

    public function __destruct() {
        curl_close($this->_curl);
    }

    public function sanitize($string, $lowercase = false) {

        // Lowercase
        if ($lowercase) {
            $string = mb_strtolower($string, 'UTF-8');
        }

        return $string;
    }

    protected function prepare($uri, $method, $timeout = 30, array $postfields = [], $verify_ssl = true, $verify_host = true) {

        curl_setopt($this->_curl, CURLOPT_URL, $this->_protocol . '://' . $this->_host . ':' . $this->_port . '/' . $uri);
        curl_setopt($this->_curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($this->_curl, CURLOPT_TIMEOUT, $timeout);

        if ($verify_ssl === false) {
          curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        if ($verify_host === false) {
          curl_setopt($this->_curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($method) {
            curl_setopt($this->_curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        if ($method == 'POST' && $postfields) {
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, json_encode($postfields));
        }
    }

    protected function execute($json = true) {

        $response    = curl_exec($this->_curl);
        $errorNumber = curl_errno($this->_curl);
        $errorText   = curl_error($this->_curl);

        if ($errorNumber > 0) {
            return false;
        }

        if ($response) {
            if ($json) {
                return json_decode($response, true);
            } else {
                return $response;
            }
        }

        return false;
    }
}
