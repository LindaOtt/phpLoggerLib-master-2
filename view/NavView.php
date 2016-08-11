<?php
namespace logger;

class NavView {

    private $ipAddress;

    public function __construct() {

    }

    public function getIp() {
        return $this->ipAddress=$_SERVER['SERVER_ADDR'];
    }

    public function handleSession() {
        if (!isset($_SESSION['timesessionstarted'])) {
            $_SESSION['timesessionstarted'] = time();
        }
        else if (time() - $_SESSION['timesessionstarted'] > 900) {
            session_regenerate_id(true);
            $_SESSION['timesessionstarted'] = time();
        }
    }
}