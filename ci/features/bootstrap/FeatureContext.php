<?php
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Testwork\Tester\Result\TestResult;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($session = null)
    {
    }

    /**
     * @Given /^I log out$/
     */
    public function iLogOut()
    {
        $this->getSession()->reset();
    }

    /**
     * @Given /^I wait for ajax response$/
     */
    public function iWaitForAjaxResponse()
    {
        $this->getSession()->wait(1000);
    }

    /**
     * @Given /^I wait "([^"]*)" milliseconds for response$/
     */
    public function iWaitMillisecondsForResponse($delay)
    {
        $this->getSession()->wait($delay);
    }

    /**
     * @Given /^I set browser window size to "([^"]*)" x "([^"]*)"$/
     */
    public function iSetBrowserWindowSizeToX($width, $height)
    {
        $this->getSession()->resizeWindow((int)$width, (int)$height, 'current');
    }

    /**
     * Click on the element with the provided xpath query
     *
     * @When I click on the element :arg1
     */
    public function iClickOnTheElement($selector)
    {
        $session = $this->getSession();
        $element = $session->getPage()->find('css', $selector);

        // If element with current selector is not found then print error
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $selector));
        }

        // Click on the founded element
        $element->click();
    }

    /**
     * @AfterStep
     */
    public function takeScreenShotAfterFailedStep(AfterStepScope $scope)
    {
        if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {
            $driver = $this->getSession()->getDriver();
            if (!($driver instanceof Selenium2Driver)) {
                return;
            }

            // Create unique folder for this inspection
            $artifactsPath = '~/artifacts/'.date('YmdHis').'/';
            if (!file_exists($artifactsPath)) {
                mkdir($artifactsPath, 0777, true);
            }

            // Create screenshot
            file_put_contents('~/artifacts/'.uniqid().'.png', $this->getSession()->getDriver()->getScreenshot());
        }
    }

    public function toggleStyle($selector)
    {
        $function = <<<JS
(function(){
  $ = jQuery;
  s = $('$selector');

  s.each(function(){
    if($(this).attr('style')) {
        $(this).attr('data-old-style', $(this).attr('style'));
        $(this).attr('style', '');
      }
      else {
        $(this).attr('style', $(this).attr('data-old-style'));
        $(this).attr('data-old-style', '');
      }
  });
})()
JS;
        $this->getSession()->executeScript($function);
    }

    /**
     * @When I fill in :arg1 in the hidden field :arg2 with selector :arg3
     */
    public function iFillInTheHiddenField($value, $field, $id)
    {
        $this->toggleStyle($id);
        $this->fillField($field, $value);
        $this->toggleStyle($id);
    }

    /**
     * @Then I hover over :arg1
     */
    public function iHoverOver($id)
    {
        $page = $this->getSession()->getPage();
        $findName = $page->find("css", $id);
        if (!$findName) {
            throw new Exception($id . " could not be found");
        } else {
            $findName->mouseOver();
        }
    }


}

