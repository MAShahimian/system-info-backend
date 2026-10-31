<?php

declare(strict_types=1);

use RadinTech\SystemInfo\Api\VersionAction;
use Yiisoft\Router\Route;

return [
    Route::get('/api/v1/modules/system-info/version')
        ->action(VersionAction::class)
        ->name('radintech/system-info/version'),
];
