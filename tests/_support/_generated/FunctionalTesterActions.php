<?php  //[STAMP] d23515a2b2bd7d18a9ca58f1fb5111fe
namespace _generated;

// This class was automatically generated by build task
// You should not change it manually as it will be overwritten on next build
// @codingStandardsIgnoreFile

trait FunctionalTesterActions
{
    /**
     * @return \Codeception\Scenario
     */
    abstract protected function getScenario();

    
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     * Get all enabled modules
     *
     * @return array
     * @see \Helper\Functional::getModules()
     */
    public function getModules() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('getModules', func_get_args()));
    }

 
    /**
     * [!] Method is generated. Documentation taken from corresponding module.
     *
     *
     * @see \Helper\Functional::getBaseUrl()
     */
    public function getBaseUrl() {
        return $this->getScenario()->runStep(new \Codeception\Step\Action('getBaseUrl', func_get_args()));
    }
}