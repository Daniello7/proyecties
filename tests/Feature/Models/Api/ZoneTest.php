<?php

use App\Models\Api\Zone;
use App\Models\Api\Guard;
use App\Models\Api\GuardReport;
use App\Models\Api\Alarm;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    // Arrange
    $this->zone = Zone::factory()->create([
        'name' => 'Test Zone',
        'location' => 'Test Location'
    ]);
});

it('has fillable fields', function () {
    // Act & Assert
    expect($this->zone->getFillable())->toBe([
        'name',
        'location'
    ]);
});

it('can create a zone with valid data', function () {
    // Arrange
    $zoneData = [
        'name' => 'New Zone',
        'location' => 'New Location'
    ];

    // Act
    $zone = Zone::create($zoneData);

    // Assert
    expect($zone)
        ->name->toBe('New Zone')
        ->location->toBe('New Location');
});

describe('relationships', function () {
    it('can have many assigned guards', function () {
        // Arrange
        $guards = Guard::factory(3)->create();

        // Act
        $this->zone->assignedGuards()->attach($guards, ['schedule' => '9-5']);

        // Assert
        expect($this->zone->assignedGuards)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(Guard::class);
    });

    it('stores pivot data for guards', function () {
        // Arrange
        $guard = Guard::factory()->create();

        // Act
        $this->zone->assignedGuards()->attach($guard, ['schedule' => '9-5']);

        // Assert
        expect($this->zone->assignedGuards->first()->pivot)
            ->schedule->toBe('9-5')
            ->created_at->not->toBeNull()
            ->updated_at->not->toBeNull();
    });

    it('can have many reports', function () {
        // Arrange & Act
        GuardReport::factory(3)->create(['zone_id' => $this->zone->id]);

        // Assert
        expect($this->zone->reports)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(GuardReport::class);
    });

    it('can have many alarms', function () {
        // Arrange & Act
        Alarm::factory(3)->create(['zone_id' => $this->zone->id]);

        // Assert
        expect($this->zone->alarms)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(Alarm::class);
    });
});

describe('ownZones scope', function () {
    it('returns all zones when user cannot read own zones', function () {
        // Arrange
        $user = User::factory()->create();
        Zone::factory(3)->create();
        Sanctum::actingAs($user, ['other-ability']);

        // Act & Assert
        expect(Zone::ownZones()->count())->toBe(4);
    });

    it('returns all zones when user has no assigned guard', function () {
        // Arrange
        $user = User::factory()->create();
        Zone::factory(3)->create();
        Sanctum::actingAs($user, ['read-own-zones']);

        // Act & Assert
        expect(Zone::ownZones()->count())->toBe(4);
    });

    it('returns only zones assigned to user guard', function () {
        // Arrange
        $guard = Guard::factory()->create();
        $user = User::factory()->create();

        $guard->user_id = $user->id;
        $guard->save();

        $zoneForGuard = Zone::factory()->create();
        $guard->zones()->attach($zoneForGuard, ['schedule' => '9-5']);
        Zone::factory(2)->create();
        Sanctum::actingAs($user, ['read-own-zones']);

        // Act & Assert
        expect(Zone::ownZones())
            ->toHaveCount(1)
            ->first()->id->toBe($zoneForGuard->id);
    });

});

it('cascades deletes for relationships', function () {
    // Arrange
    $guard = Guard::factory()->create();
    $this->zone->assignedGuards()->attach($guard, ['schedule' => '9-5']);
    GuardReport::factory()->create(['zone_id' => $this->zone->id]);
    Alarm::factory()->create(['zone_id' => $this->zone->id]);

    // Act
    $this->zone->delete();

    // Assert
    $this->assertDatabaseMissing('guard_zone', ['zone_id' => $this->zone->id]);
    $this->assertDatabaseMissing('guard_reports', ['zone_id' => $this->zone->id]);
    $this->assertDatabaseMissing('alarms', ['zone_id' => $this->zone->id]);
});