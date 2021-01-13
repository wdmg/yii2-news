<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function getModules()
    {
        return $this->getModule('Yii2')->getModules();
    }

    public function getBaseUrl()
    {
        return $this->getModule('Yii2')->_getUrl();
    }
}
