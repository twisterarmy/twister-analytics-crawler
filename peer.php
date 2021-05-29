<?php

require(__DIR__ . '/config.php');

require(__DIR__ . '/curl/curl.php');
require(__DIR__ . '/curl/peer.php');
require(__DIR__ . '/curl/geoplugin.php');

require(__DIR__ . '/model/model.php');
require(__DIR__ . '/model/ip.php');
require(__DIR__ . '/model/log.php');

$curlPeer       = new CurlPeer(TWISTER_PROTOCOL, TWISTER_HOST, TWISTER_PORT, TWISTER_USERNAME, TWISTER_PASSWORD);
$curlGeoPlugin  = new CurlGeoPlugin(GEOPLUGIN_PROTOCOL, GEOPLUGIN_HOST, GEOPLUGIN_PORT);

$modelIp        = new ModelIp(DB_DATABASE, DB_HOSTNAME, DB_PORT, DB_USERNAME, DB_PASSWORD);
$modelLog       = new ModelLog(DB_DATABASE, DB_HOSTNAME, DB_PORT, DB_USERNAME, DB_PASSWORD);

$isOnlinePeers  = [];
$isOfflinePeers = [];

$toOnlinePeers  = [];
$toOfflinePeers = [];
$newPeers       = [];
$onlinePeers    = [];

// Get online peers list
foreach ($modelIp->getIsOnlineIps() as $isOnlinePeer) {
  $isOnlinePeers[] = $isOnlinePeer['address'];
}

// Get offline peers list
foreach ($modelIp->getIsOfflineIps() as $isOfflinePeer) {
  $isOfflinePeers[] = $isOfflinePeer['address'];
}

// Reset peers online
$modelIp->resetIsOnline();

// Get current peers
if ($peers = $curlPeer->getAll()) {

  foreach ($peers as $peer) {

    if (isset($peer['addr'])) {

      // Parse response
      if (false !== preg_match('/(.*):(\d+)$/', $peer['addr'], $matches)) {

        if (isset($matches[1]) && isset($matches[2])) {

          // IP exist
          if (!$ipId = $modelIp->exists($matches[1])) {

            // Save IP
            $ipId = $modelIp->add($matches[1], $matches[2]);

            // Get geo info
            if ($location = $curlGeoPlugin->getLocation($matches[1])) {

              $modelIp->updateGeoData($ipId,
                                      $location['geoplugin_countryCode'],
                                      $location['geoplugin_city'],
                                      $location['geoplugin_latitude'],
                                      $location['geoplugin_longitude']);
            } else {
              $modelLog->add(_('Could not receive geolocation details'));
            }

            $newPeers[] = $matches[1];
          }

          // Peer switching online
          if (in_array($matches[1], $isOfflinePeers)) {
            $toOnlinePeers[] = $matches[1];
          }

          // Add online peers to registry
          $onlinePeers[] = $matches[1];

          // Set peer as online
          $modelIp->setIsOnline($ipId);

          // Update online time
          $modelIp->addOnline($ipId,
                              $peer['startingheight'],
                              $peer['conntime'],
                              $peer['lastsend'],
                              $peer['lastrecv']);
        } else {
          $modelLog->add(_('Could not extract peer address or port'));
        }
      } else {
        $modelLog->add(_('Could not parse peer address'));
      }
    } else {
      $modelLog->add(_('Could not parse RPC response'));
    }
  }
} else {
  $modelLog->add(_('Could not connect to twister peer'));
}

// Alert if peer(s) going to offline
$toOfflinePeers = array_diff($isOnlinePeers, $onlinePeers);

if (EMAIL_OFFLINE_PEERS && $toOfflinePeers) {
  mail(EMAIL_OFFLINE_PEERS, sprintf(_('Peer(s) switched to offline')), implode("\r\n", $toOfflinePeers));
}

// Alert if peer(s) going to online
if (EMAIL_ONLINE_PEERS && $toOnlinePeers) {
  mail(EMAIL_ONLINE_PEERS, sprintf(_('Peer(s) switched to online')), implode("\r\n", $toOnlinePeers));
}

// Alert if new peer(s) available
if (EMAIL_NEW_PEERS && $newPeers) {
  mail(EMAIL_NEW_PEERS, sprintf(_('New peer(s) added')), implode("\r\n", $newPeers));
}
