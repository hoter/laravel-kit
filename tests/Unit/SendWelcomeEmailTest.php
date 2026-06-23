<?php

namespace Tests\Unit;

use App\Jobs\SendWelcomeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('SendWelcomeEmail sends welcome mail to user', function () {
    Mail::fake();

    $user = User::factory()->create(['email' => 'test@example.com']);

    (new SendWelcomeEmail($user))->handle();

    Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
        return $mail->hasTo('test@example.com')
            && $mail->user->id === $user->id;
    });
});

test('SendWelcomeEmail mail has correct subject', function () {
    Mail::fake();

    $user = User::factory()->create();

    (new SendWelcomeEmail($user))->handle();

    Mail::assertSent(WelcomeMail::class, function ($mail) {
        return $mail->envelope()->subject === 'Welcome to our application!';
    });
});

test('SendWelcomeEmail mail has correct view', function () {
    Mail::fake();

    $user = User::factory()->create();

    (new SendWelcomeEmail($user))->handle();

    Mail::assertSent(WelcomeMail::class, function ($mail) {
        return $mail->content()->view === 'emails.welcome';
    });
});
