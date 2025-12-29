<?php


use App\Http\Controllers\WeezeventWebhookController;

Route::post('/webhooks/weezevent/scan', [WeezeventWebhookController::class, 'scan']);
