<?php
namespace logger;

class NavView {

    private $ipAddress;

    public function __construct() {

    }

    public function getIp() {
        return $this->ipAddress=$_SERVER['SERVER_ADDR'];
    }

}