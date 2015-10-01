<?php

use tests\codeception\_pages\RecipePage;
use tests\codeception\_pages\LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that recipe works');

$loginPage = LoginPage::openBy($I);
$loginPage->login('admin', 'admin');

$recipe = RecipePage::openBy($I);
$I->seeInTitle('Recipe');
$I->dontSee('Energy');
$I->dontSee('Name');
$I->dontSee('Quantity');
$I->dontSee('Proteins');

$I->click('Choose a dish');
$I->click($recipe->selectionCrepes);
$I->waitForText('Energy');
$I->see('Name');
$I->see('Quantity');
$I->see('Proteins');

