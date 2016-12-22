<?php

namespace Features\Bootstrap;

use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\MinkExtension\Context\MinkContext;

class AcceptanceContext extends MinkContext
{
    /**
     * @AfterScenario @javascript
     * @param AfterScenarioScope $scope
     */
    public function screenshotOnFailure(AfterScenarioScope $scope)
    {
        $dir = 'var/logs/phantomJs';
        if ($scope->getTestResult()->isPassed() === false) {
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $imageData = $this->getSession()->getDriver()->getScreenshot();
            $imagePath = $dir . '/' . time() . '.png';
            file_put_contents($imagePath, $imageData);
        }
    }

    /**
     * @Then /^I wait for the ajax response$/
     */
    public function iWaitForTheAjaxResponse()
    {
        $this->getSession()->wait(5000, '(window.getAjaxConnections() === 0)');
    }
}
