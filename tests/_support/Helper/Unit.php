<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    public function getModules()
    {
        return $this->getModule('Yii2')->getModules();
    }

    public function getBaseUrl()
    {
        return sprintf(
            "%s://%s%s",
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ''
        );
    }
}
