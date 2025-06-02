<?php

namespace Tests\Feature\Api;

use App\Models\Api\Guard;
use App\Models\Api\GuardReport;
use App\Models\Api\Zone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);
});

it('can list guard reports', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    GuardReport::factory(3)->create([
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);

    // Act
    $response = $this->getJson('/api/guard-reports');

    // Assert
    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'zone' => [
                        'id',
                        'name',
                        'location'
                    ],
                    'guard' => [
                        'id',
                        'dni',
                        'name'
                    ],
                    'entry_time',
                    'exit_time',
                    'incident'
                ]
            ]
        ]);
});

it('can create a guard report', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $reportData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHours(8)->toDateTimeString(),
        'incident' => 'Test incident description'
    ];

    // Act
    $response = $this->postJson('/api/guard-reports', $reportData);

    // Assert
    $response->assertCreated();
    $this->assertDatabaseHas('guard_reports', $reportData);
});

it('can show a specific guard report', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $report = GuardReport::factory()->create([
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);

    // Act
    $response = $this->getJson("/api/guard-reports/{$report->id}");

    // Assert
    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'zone' => [
                    'id',
                    'name',
                    'location'
                ],
                'guard' => [
                    'id',
                    'dni',
                    'name'
                ],
                'entry_time',
                'exit_time',
                'incident'
            ]
        ]);
});

it('returns 404 when showing non-existent report', function () {
    // Act
    $response = $this->getJson("/api/guard-reports/" . (GuardReport::max('id') + 1));

    // Assert
    $response->assertNotFound();
});

it('can update a guard report', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $report = GuardReport::factory()->create([
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);
    
    $updateData = [
        'guard_id' => $guard->id,
        'zone_id' => $zone->id,
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHours(8)->toDateTimeString(),
        'incident' => 'Updated incident description'
    ];

    // Act
    $response = $this->putJson("/api/guard-reports/{$report->id}", $updateData);

    // Assert
    $response->assertOk();
    $this->assertDatabaseHas('guard_reports', $updateData);
});

it('can delete a guard report', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $zone = Zone::factory()->create();
    $report = GuardReport::factory()->create([
        'guard_id' => $guard->id,
        'zone_id' => $zone->id
    ]);

    // Act
    $response = $this->deleteJson("/api/guard-reports/{$report->id}");

    // Assert
    $response->assertOk();
    $this->assertDatabaseMissing('guard_reports', ['id' => $report->id]);
});

it('validates required fields when creating report', function () {
    // Act
    $response = $this->postJson('/api/guard-reports', []);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['guard_id', 'zone_id', 'entry_time', 'exit_time']);
});

it('validates guard exists when creating report', function () {
    // Arrange
    $zone = Zone::factory()->create();
    $nonExistentGuardId = Guard::max('id') + 1;
    $reportData = [
        'guard_id' => $nonExistentGuardId,
        'zone_id' => $zone->id,
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHours(8)->toDateTimeString()
    ];

    // Act
    $response = $this->postJson('/api/guard-reports', $reportData);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['guard_id']);
});

it('validates zone exists when creating report', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $nonExistentZoneId = Zone::max('id') + 1;
    $reportData = [
        'guard_id' => $guard->id,
        'zone_id' => $nonExistentZoneId,
        'entry_time' => now()->toDateTimeString(),
        'exit_time' => now()->addHours(8)->toDateTimeString()
    ];

    // Act
    $response = $this->postJson('/api/guard-reports', $reportData);

    // Assert
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['zone_id']);
});