<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Define The Application's Command Schedule
|--------------------------------------------------------------------------
|
| Here you may define all of your scheduled tasks for your application.
| The Laravel 11 scheduler works differently than previous versions.
| This file is automatically loaded by the framework.
|
*/

Schedule::command('orders:cancel-expired')->hourly();