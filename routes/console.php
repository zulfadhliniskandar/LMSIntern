<?php

use App\Models\Subjects;
use App\Models\subjectTaking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Wirechat\Wirechat\Models\Group;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


