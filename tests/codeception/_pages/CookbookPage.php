<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents about page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class CookbookPage extends BasePage
{
    public $route = 'site/cookbook';

    public $selectionVictor = '/html/body/div[1]/div/div[2]/div[2]/div/div[1]/div/div/ul/li[4]';
    public $selectionTesco  = '/html/body/div[1]/div/div[2]/div[2]/div/div[2]/div/div/ul/li';

    public $buttonMoreGuests = '/html/body/div[1]/div/div[2]/div[2]/div/div[3]/div/span[4]/button';
}
