<?php

use App\Models\Api\Guard;
use App\Models\Api\Zone;
use App\Models\Api\Alarm;
use App\Models\Api\GuardReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Arrange
    $this->guard = Guard::factory()->create([
        'name' => 'Test Guard',
        'dni' => '12345678A',
        'user_id' => User::factory()
    ]);
});

it('has fillable fields', function () {
    // Assert
    expect($this->guard->getFillable())->toBe([
        'name',
        'dni',
        'user_id'
    ]);
});

describe('scopes', function () {
    it('filters guards by name', function () {
        // Arrange
        Guard::factory()->create(['name' => 'John Doe']);
        Guard::factory()->create(['name' => 'Jane Smith']);

        // Act & Assert
        expect(Guard::name('John')->count())->toBe(1)
            ->and(Guard::name('doe')->first()->name)->toBe('John Doe');
    });

    it('filters guards by dni', function () {
        // Arrange
        Guard::factory()->create(['dni' => '11111111A']);
        Guard::factory()->create(['dni' => '22222222B']);

        // Act & Assert
        expect(Guard::dni('111')->count())->toBe(1)
            ->and(Guard::dni('111')->first()->dni)->toBe('11111111A');
    });

    describe('ownGuard scope', function () {
        it('returns unfiltered query when user cannot read own guard', function () {
            // Arrange
            $user = User::factory()->create();
            $guard = Guard::factory()->create(['user_id' => $user->id]);
            Sanctum::actingAs($user, ['other-ability']);

            // Act & Assert
            expect(Guard::ownGuard()->count())->toBe(2); // incluye el guard del beforeEach
        });

        it('returns unfiltered query when user has no assigned guard', function () {
            // Arrange
            $user = User::factory()->create();
            Sanctum::actingAs($user, ['read-own-guard']);

            // Act & Assert
            expect(Guard::ownGuard()->count())->toBe(1);
        });

        it('returns only assigned guard when user has permission', function () {
            // Arrange
            $user = User::factory()->create();
            $guard = Guard::factory()->create(['user_id' => $user->id]);
            Guard::factory(2)->create();

            Sanctum::actingAs($user, ['read-own-guard']);

            // Act & Assert
            expect(Guard::ownGuard()->get())
                ->toHaveCount(1)
                ->first()->id->toBe($guard->id);
        });
    });
});

describe('relationships', function () {
    it('can have many zones with pivot data', function () {
        // Arrange
        $zones = Zone::factory(3)->create();

        // Act
        $this->guard->zones()->attach($zones, ['schedule' => '9-5']);

        // Assert
        expect($this->guard->zones)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(Zone::class)
            ->and($this->guard->zones->first()->pivot)
            ->schedule->toBe('9-5')
            ->created_at->not->toBeNull()
            ->updated_at->not->toBeNull();
    });

    it('can have many alarms with pivot data', function () {
        // Arrange
        $alarms = Alarm::factory(3)->create();
        $pivotData = [
            'date' => now(),
            'is_false_alarm' => true,
            'notes' => 'Test notes'
        ];

        // Act
        $this->guard->alarms()->attach($alarms, $pivotData);
        $this->guard->refresh(); // Recargamos el modelo para obtener las relaciones actualizadas

        // Assert
        expect($this->guard->alarms)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(Alarm::class);

        $pivot = $this->guard->alarms->first()->pivot;

        expect($pivot)
            ->date->not->toBeNull()
            ->and($pivot->is_false_alarm)->toBe(1) // Los booleanos en SQLite se almacenan como 1/0
            ->and($pivot->notes)->toBe('Test notes')
            ->and($pivot->created_at)->not->toBeNull()
            ->and($pivot->updated_at)->not->toBeNull();
    });

    it('can have many reports', function () {
        // Arrange & Act
        GuardReport::factory(3)->create(['guard_id' => $this->guard->id]);

        // Assert
        expect($this->guard->reports)
            ->toHaveCount(3)
            ->each->toBeInstanceOf(GuardReport::class);
    });
});

describe('global scope', function () {
    it('loads zones relation when with_zones query parameter is true', function () {
        // Arrange
        $request = request()->merge(['with_zones' => 'true']);

        // Act
        $guard = Guard::first();

        // Assert
        expect($guard->relationLoaded('zones'))->toBeTrue();
    });

    it('does not load zones relation when with_zones query parameter is false', function () {
        // Arrange
        $request = request()->merge(['with_zones' => 'false']);

        // Act
        $guard = Guard::first();

        // Assert
        expect($guard->relationLoaded('zones'))->toBeFalse();
    });

    it('does not load zones relation when with_zones query parameter is missing', function () {
        // Act
        $guard = Guard::first();

        // Assert
        expect($guard->relationLoaded('zones'))->toBeFalse();
    });
});