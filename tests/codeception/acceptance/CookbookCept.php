<?php

use tests\codeception\_pages\CookbookPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that cookbook page works');

$cookbook = CookbookPage::openBy($I);
$I->dontSee('Product');
$I->dontSee('Quantity');
$I->see('Choose a boat');
$I->see('Choose a shop');
$I->click($cookbook->buttonMoreGuests);;

$I->click('Choose a boat');
$I->click($cookbook->selectionVictor);
$I->dontSee('Product');
$I->dontSee('Quantity');

$I->click('Choose a shop');
$I->click($cookbook->selectionTesco);
$I->dontSee('Product');
$I->dontSee('Quantity');

