<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    public function getModules()
    {
        return $this->getModule('Yii2')->getModules();
    }

    public function getBaseUrl()
    {
        return $this->getModule('Yii2')->_getUrl();
    }

    public function amOnPageRoute(array $route)
    {
        $this->amOnPage(\yii\helpers\Url::toRoute($route));
    }
}
