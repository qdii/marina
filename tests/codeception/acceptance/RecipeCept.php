<?php

use tests\codeception\_pages\RecipePage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that recipe works');

$recipe = RecipePage::openBy($I);
$I->dontSee('Energy');
$I->dontSee('Name');
$I->dontSee('Quantity');
$I->dontSee('Proteins');
