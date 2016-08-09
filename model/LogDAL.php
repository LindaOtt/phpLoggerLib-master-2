<?php

namespace logger;

class LogDAL {

    private static $logCollection;

    public function __construct(LogCollection $logCollection) {
        $this->logCollection = $logCollection;
    }


    public function getLogItems() {
        $debugItems = "";
        foreach ($this->logCollection->getList() as $item) {
            $debugItems .= $this->showDebugItem($item);
        }
    }

}