<?php

$factory->define(App\Invitation::class, function () {
    return [
        'email' => 'somebody@example.com',
        'code' => 'TESTCODE1234',
    ];
});
