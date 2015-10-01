<?php

use tests\codeception\_pages\CalendarPage;
use tests\codeception\_pages\LoginPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that calendar works');

$loginPage = LoginPage::openBy($I);
$loginPage->login('admin', 'admin');

CalendarPage::openBy($I);
$I->seeInTitle('Calendar');

