<?php

use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    // Creamos un usuario para las relaciones
    User::factory()->create();

    // Arrange
    Package::factory(3)->create([
        'deleted_at' => Carbon::now()->subDays(31)
    ]);

    Package::factory(2)->create([
        'deleted_at' => Carbon::now()->subDays(29)
    ]);

    Package::factory(2)->create([
        'deleted_at' => null
    ]);
});

it('elimina permanentemente los paquetes con más de 30 días en la papelera', function () {
    // Act & Assert
    // Verificamos el número inicial de paquetes
    expect(Package::withTrashed()->count())->toBe(7)
        ->and(Package::onlyTrashed()->count())->toBe(5);

    // Ejecutamos el comando
    $this->artisan('packages:clear-old')
        ->assertSuccessful()
        ->expectsOutput('Se eliminaron 3 paquetes de la papelera.');

    // Verificamos que solo se eliminaron los paquetes antiguos
    expect(Package::withTrashed()->count())->toBe(4)
        ->and(Package::onlyTrashed()->count())->toBe(2)
        ->and(Package::count())->toBe(2);

    // Verificamos que los paquetes restantes son los correctos
    expect(Package::onlyTrashed()->get()->every(fn($package) => $package->deleted_at->isAfter(Carbon::now()->subMonth())
    ))->toBeTrue();
});

it('no elimina nada si no hay paquetes antiguos', function () {
    // Arrange
    // Limpiamos los datos de prueba anteriores
    Package::truncate();

    // Creamos un nuevo usuario para este test
    User::factory()->create();

    // Creamos solo paquetes recientes
    Package::factory(3)->create([
        'deleted_at' => Carbon::now()->subDays(15)
    ]);

    // Act
    $this->artisan('packages:clear-old')
        ->assertSuccessful()
        ->expectsOutput('Se eliminaron 0 paquetes de la papelera.');

    // Assert
    expect(Package::withTrashed()->count())->toBe(3);
});