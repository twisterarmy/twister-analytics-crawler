<?php

class ModelLog extends Model {

    public function add($message) {

        try {

            $query = $this->db->prepare('INSERT INTO `log` SET `message` = ?, `timeAdded` = UNIX_TIMESTAMP()');

            $query->execute([$message]);

            return $this->db->lastInsertId();

        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }
}
