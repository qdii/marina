<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents contact page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class RecipePage extends BasePage
{
    public $route = 'site/recipe';

    public $selectionBread = '/html/body/div[1]/div/div[1]/div[1]/div/div[1]/div/div/ul/li[2]';
    public $selectionCrepes = '/html/body/div[1]/div/div[2]/div[2]/div/div[1]/div/div/ul/li[4]';
}
