<?php

use tests\codeception\_pages\RecipePage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that contact works');

$contactPage = RecipePage::openBy($I);

