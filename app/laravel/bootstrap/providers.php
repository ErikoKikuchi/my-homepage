<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use PragmaRx\Google2FALaravel\ServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    ServiceProvider::class,
];
