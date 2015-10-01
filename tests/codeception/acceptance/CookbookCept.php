<?php

use tests\codeception\_pages\CookbookPage;
use tests\codeception\_pages\LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that cookbook page works');

$loginPage = LoginPage::openBy($I);
$loginPage->login('admin', 'admin');

$cookbook = CookbookPage::openBy($I);
$I->seeInTitle('Cookbook');
$I->dontSee('Product');
$I->dontSee('Quantity');
$I->see('Choose a cruise');
$I->see('Choose a shop');
$I->click($cookbook->buttonMoreGuests);;

$I->click('Choose a cruise');
$I->click($cookbook->selectionVictor);
$I->dontSee('Product');
$I->dontSee('Quantity');

$I->click('Choose a shop');
$I->click($cookbook->selectionTesco);
$I->waitForText('Product', 3);
$I->see('Quantity');

