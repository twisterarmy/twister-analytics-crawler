<?php

require(__DIR__ . '/config.php');

require(__DIR__ . '/curl/curl.php');
require(__DIR__ . '/curl/torproject.php');

require(__DIR__ . '/model/model.php');
require(__DIR__ . '/model/ip.php');
require(__DIR__ . '/model/log.php');

$curlTorProject = new CurlTorProject(TORPROJECT_PROTOCOL, TORPROJECT_HOST, TORPROJECT_PORT);

$modelIp  = new ModelIp(DB_DATABASE, DB_HOSTNAME, DB_PORT, DB_USERNAME, DB_PASSWORD);
$modelLog = new ModelLog(DB_DATABASE, DB_HOSTNAME, DB_PORT, DB_USERNAME, DB_PASSWORD);

$exitNodes = [];

// Get TOR registry
if ($torProjectExitNodes = $curlTorProject->getExitNodes()) {

  foreach ($torProjectExitNodes as $exitNode) {
    $exitNodes[] = $exitNode;
  }

  // Get IPs
  foreach ($modelIp->getIps() as $ip) {

    if (in_array($ip['address'], $exitNodes)) {
      $modelIp->setIsTOR($ip['ipId']);
    }
  }

} else {

  $modelLog->add(_('Could not parse TorProject response'));
}
