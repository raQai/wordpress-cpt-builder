<?php

namespace BIWS\EventManager;

spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) !== 0) {
        return;
    }
    $className = str_replace(__NAMESPACE__, "", $className);
    $className = str_replace("\\",  DIRECTORY_SEPARATOR, $className);
    include_once BIWS_EventManager__PLUGIN_DIR_PATH . 'classes' . $className . '.class.php';
});
