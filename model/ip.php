<?php

class ModelIp extends Model {

    public function exists($address) {

        try {

            $query = $this->db->prepare('SELECT `ipId` FROM  `ip` WHERE `address` = ? LIMIT 1');

            $query->execute([$address]);

            return $query->rowCount() ? $query->fetch()['ipId'] : false;

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function getIps() {

        try {

            $query = $this->db->prepare('SELECT `ipId`, `address` FROM  `ip`');

            $query->execute();

            return $query->rowCount() ? $query->fetchAll() : [];

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function getIsOnlineIps() {

        try {

            $query = $this->db->prepare('SELECT `address` FROM  `ip` WHERE `isOnline` = "1"');

            $query->execute();

            return $query->rowCount() ? $query->fetchAll() : [];

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function getIsOfflineIps() {

        try {

            $query = $this->db->prepare('SELECT `address` FROM  `ip` WHERE `isOnline` = "0"');

            $query->execute();

            return $query->rowCount() ? $query->fetchAll() : [];

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function add($address, $port) {

        try {

            $query = $this->db->prepare('INSERT INTO `ip` SET `address` = ?, `port` = ?, `isOnline` = "0", `isTOR` = "0"');

            $query->execute([$address, $port]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function addOnline($ipId,
                              $startingHeight,
                              $timeConnection,
                              $timeLastSend,
                              $timeLastReceive,
                              $bytesSent,
                              $bytesReceive,
                              $banscore,
                              $inbound,
                              $version,
                              $subVersion,
                              $subVersionCRC32,
                              $syncNode) {

        try {

            $query = $this->db->prepare('INSERT INTO `ipOnline` SET `ipId`            = ?,
                                                                    `startingHeight`  = ?,
                                                                    `timeConnection`  = ?,
                                                                    `timeLastSend`    = ?,
                                                                    `timeLastReceive` = ?,
                                                                    `bytesSent`       = ?,
                                                                    `bytesReceive`    = ?,
                                                                    `banscore`        = ?,
                                                                    `inbound`         = ?,
                                                                    `version`         = ?,
                                                                    `subVersion`      = ?,
                                                                    `subVersionCRC32` = ?,
                                                                    `syncNode`        = ?,

                                                                    `timeAdded` = UNIX_TIMESTAMP()');

            $query->execute([ $ipId,
                              $startingHeight,
                              $timeConnection,
                              $timeLastSend,
                              $timeLastReceive,
                              $bytesSent,
                              $bytesReceive,
                              $banscore,
                              $inbound,
                              $version,
                              $subVersion,
                              $subVersionCRC32,
                              $syncNode]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function updateGeoData($ipId, $countryCode, $city, $latitude, $longitude) {

        try {

            $query = $this->db->prepare('UPDATE `ip` SET  `countryCode` = ?,
                                                          `city`        = ?,
                                                          `latitude`    = ?,
                                                          `longitude`   = ?

                                                           WHERE `ipId`  = ?
                                                           LIMIT 1');

            $query->execute([$countryCode, $city, $latitude, $longitude, $ipId]);

            return $query->rowCount();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function resetIsOnline() {

        try {

            $query = $this->db->prepare('UPDATE `ip` SET  `isOnline` = "0"');

            $query->execute();

            return $query->rowCount();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function setIsOnline($ipId) {

        try {

            $query = $this->db->prepare('UPDATE `ip` SET `isOnline` = "1" WHERE `ipId` = ? LIMIT 1');

            $query->execute([$ipId]);

            return $query->rowCount();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }

    public function setIsTOR($ipId) {

        try {

            $query = $this->db->prepare('UPDATE `ip` SET `isTOR` = "1" WHERE `ipId` = ? LIMIT 1');

            $query->execute([$ipId]);

            return $query->rowCount();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }
}
