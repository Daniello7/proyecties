<?php

namespace Tests\Feature\Listeners;

use App\Events\NotifyContactPackageEvent;
use App\Mail\NotifyContactPackageMail;
use App\Models\Package;
use App\Models\User;
use App\Models\InternalPerson;
use Illuminate\Support\Facades\Mail;

it('sends email notification with package information', function () {
    // Arrange
    Mail::fake();
    
    $receiver = User::factory()->create(['name' => 'John Doe']);
    $deliver = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'email' => 'jane.smith@example.com',
        'contract_type' => 'Permanent',
        'hired_at' => now()
    ]);

    $package = Package::factory()->state([
        'type' => 'entry',
        'external_entity' => 'ACME Corp',
        'agency' => 'SEUR',
        'package_count' => 3,
        'entry_time' => '2025-06-05 10:00:00',
        'receiver_user_id' => $receiver->id,
        'deliver_user_id' => $deliver->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => 'Handle with care'
    ])->create();

    $expectedMessage = __('Hello!') . " {$internalPerson->person->name}.\n\n\t" .
        __('New package has arrived') . ":\n\t" .
        __('Sender') . ": ACME Corp\n\t" .
        __('Agency') . ": SEUR\n\t" .
        __('Package Count') . ": 3\n\t" .
        __('Arrival time') . ": 2025-06-05 10:00:00\n\t" .
        __('Receiver') . ": John Doe\n\n" .
        __('Comment') . ": Handle with care\n";

    // Act
    event(new NotifyContactPackageEvent($package));

    // Assert
    Mail::assertQueued(NotifyContactPackageMail::class, function ($mail) use ($internalPerson, $expectedMessage) {
        return $mail->hasTo($internalPerson->email) && 
               $mail->message === $expectedMessage;
    });
});

it('sends email notification without comment when package has no comment', function () {
    // Arrange
    Mail::fake();
    
    $receiver = User::factory()->create(['name' => 'John Doe']);
    $deliver = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'email' => 'jane.smith@example.com',
        'contract_type' => 'Permanent',
        'hired_at' => now()
    ]);

    $package = Package::factory()->state([
        'type' => 'entry',
        'external_entity' => 'ACME Corp',
        'agency' => 'SEUR',
        'package_count' => 3,
        'entry_time' => '2025-06-05 10:00:00',
        'receiver_user_id' => $receiver->id,
        'deliver_user_id' => $deliver->id,
        'internal_person_id' => $internalPerson->id,
        'comment' => ''
    ])->create();

    $expectedMessage = __('Hello!') . " {$internalPerson->person->name}.\n\n\t" .
        __('New package has arrived') . ":\n\t" .
        __('Sender') . ": ACME Corp\n\t" .
        __('Agency') . ": SEUR\n\t" .
        __('Package Count') . ": 3\n\t" .
        __('Arrival time') . ": 2025-06-05 10:00:00\n\t" .
        __('Receiver') . ": John Doe\n\n";

    // Act
    event(new NotifyContactPackageEvent($package));

    // Assert
    Mail::assertQueued(NotifyContactPackageMail::class, function ($mail) use ($internalPerson, $expectedMessage) {
        return $mail->hasTo($internalPerson->email) && 
               $mail->message === $expectedMessage;
    });
});

it('queues exactly one email per event', function () {
    // Arrange
    Mail::fake();
    
    $receiver = User::factory()->create();
    $deliver = User::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'contract_type' => 'Permanent',
        'hired_at' => now()
    ]);
    
    $package = Package::factory()->state([
        'type' => 'entry',
        'agency' => 'SEUR',
        'package_count' => 1,
        'external_entity' => 'Test Corp',
        'receiver_user_id' => $receiver->id,
        'deliver_user_id' => $deliver->id,
        'internal_person_id' => $internalPerson->id,
        'entry_time' => '2025-06-05 10:00:00'
    ])->create();

    // Act
    event(new NotifyContactPackageEvent($package));

    // Assert
    Mail::assertQueued(NotifyContactPackageMail::class, 1);
});