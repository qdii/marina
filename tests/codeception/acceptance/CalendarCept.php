<?php

use tests\codeception\_pages\CalendarPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that about works');
CalendarPage::openBy($I);
$I->see('Calendar', 'h1');
