<?php

use tests\codeception\_pages\CalendarPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that calendar works');
CalendarPage::openBy($I);

