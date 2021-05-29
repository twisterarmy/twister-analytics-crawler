<?php

class CurlPeer extends Curl {

    public function getAll() {

        $this->prepare('', 'POST', 30, ['jsonrpc' => '2.0',
                                        'method'  => 'getpeerinfo',
                                        'params'  => [],
                                        'id'      => 1], false, false);

        if ($response = $this->execute()) {

            if (isset($response['result'])) {

              $peers = [];
              foreach ($response['result'] as $peer) {

                  # @TODO validate
                  if (isset($peer['addr'])) {
                      $peers[] = $peer;
                  }
              }

              return $peers;
            }
        }

        return false;
    }
}
