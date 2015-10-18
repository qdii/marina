<?php

use tests\codeception\_pages\RecipePage;
use tests\codeception\_pages\LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('duplicate a recipe');

$loginPage = LoginPage::openBy($I);
$loginPage->login('admin', 'admin');

$recipe = RecipePage::openBy($I);

$I->click('Choose a dish');
$I->click($recipe->selectionCrepes);
$I->waitForText('Energy');

$I->click('#copy-dish');
$I->waitForText('Create a dish from selection');
$I->fillField('input[name="CopyDish[name]"]', 'Test Dish');
$I->click('#submit-copy');
$I->dontSee('Create a dish from selection');
