<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('posts:api-fetch')->everyFiveMinutes();
