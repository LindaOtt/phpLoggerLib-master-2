<?php
namespace view;

class NavView {

    private $ipAddress;

    /**
     * Starts a new session each 15 minutes
     */
    public function handleSession() {
        session_start();
        if (!isset($_SESSION['timesessionstarted'])) {
            $_SESSION['timesessionstarted'] = time();
            $id = session_id();
        }
        else if (time() - $_SESSION['timesessionstarted'] > 900) {
            session_regenerate_id(true);
            $_SESSION['timesessionstarted'] = time();
            $id = session_id();
        }
    }
}