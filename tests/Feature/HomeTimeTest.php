<?php

use Carbon\Carbon;
use App\Models\User;

it('shows custom current time on home page', function () {
    $user = User::factory()->create();

    Carbon::setTestNow(Carbon::parse('2026-05-11 02:55:00', 'Asia/Kuala_Lumpur'));

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSee('2:55 AM');

    Carbon::setTestNow(); // reset
});
