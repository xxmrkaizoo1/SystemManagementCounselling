<?php
// use Carbon\Carbon;
// use App\Models\User;
// use App\Models\Role;

// it('shows custom current time on home page', function () {
//     $user = User::factory()->create();

//     $studentRole = Role::firstOrCreate(['name' => 'student']);
//     $user->roles()->syncWithoutDetaching([$studentRole->id]);

//     Carbon::setTestNow(Carbon::parse('2026-05-11 02:10:00', 'Asia/Kuala_Lumpur'));

//     $response = $this->actingAs($user)->get(route('home'));
//     $response->assertRedirect(route('home.session'));

//     $dashboard = $this->actingAs($user)->get(route('home.session'));
//     $dashboard->assertSee('2:10 AM');

//     Carbon::setTestNow();
// });
