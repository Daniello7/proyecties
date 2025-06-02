<?php

namespace Tests\Feature\Livewire;

use App\Livewire\InternalPersonTable;
use App\Models\InternalPerson;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'porter']);
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'rrhh']);
});

it('shows basic columns for porter role', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');
    
    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class);

    $component->assertSee([__('Nº Employer'), __('Name'), __('Last Name'), __('Actions')])
        ->assertDontSee([__('Email'), __('Phone')]);
});

it('shows additional columns for admin role', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    
    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class);

    $component->assertSee([
        __('Nº Employer'),
        __('Name'),
        __('Last Name'),
        __('Email'),
        __('Phone'),
        __('Actions')
    ]);
});

it('shows additional columns for rrhh role', function () {
    $user = User::factory()->create();
    $user->assignRole('rrhh');
    
    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class);

    $component->assertSee([
        __('Nº Employer'),
        __('Name'),
        __('Last Name'),
        __('Email'),
        __('Phone'),
        __('Actions')
    ]);
});

it('filters internal people by search term', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');

    $person1 = Person::factory()->create(['name' => 'John Doe']);
    $person2 = Person::factory()->create(['name' => 'Jane Smith']);
    
    $internalPerson1 = InternalPerson::factory()->create(['person_id' => $person1->id]);
    $internalPerson2 = InternalPerson::factory()->create(['person_id' => $person2->id]);

    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class)
        ->set('search', 'John');

    $component->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('sorts internal people by id', function () {
    $user = User::factory()->create();
    $user->assignRole('porter');

    $person1 = Person::factory()->create(['name' => 'AAA']);
    $person2 = Person::factory()->create(['name' => 'BBB']);
    
    InternalPerson::factory()->create(['person_id' => $person2->id]);
    InternalPerson::factory()->create(['person_id' => $person1->id]);

    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class)
        ->call('sortBy', 'internal_people.id');

    $component->assertSeeInOrder(['AAA', 'BBB']);
});

it('opens edit modal for admin role', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => $person->id,
        'email' => 'test@example.com',
        'phone' => '1234567890'
    ]);

    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class)
        ->call('openModal', 'editInternalPerson', $internalPerson->id);

    $component->assertSet('activeModal', 'editInternalPerson')
        ->assertSet('id', $internalPerson->id)
        ->assertSet('email', 'test@example.com')
        ->assertSet('phone', '1234567890');
});

it('updates internal person successfully', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => $person->id
    ]);

    $newData = [
        'email' => 'new@example.com',
        'phone' => '0987654321',
        'address' => 'New Address',
        'country' => 'New Country',
        'city' => 'New City',
        'zip_code' => '12345',
        'contract_type' => 'Full-time'
    ];

    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class)
        ->call('openModal', 'editInternalPerson', $internalPerson->id)
        ->set('email', $newData['email'])
        ->set('phone', $newData['phone'])
        ->set('address', $newData['address'])
        ->set('country', $newData['country'])
        ->set('city', $newData['city'])
        ->set('zip_code', $newData['zip_code'])
        ->set('contract_type', $newData['contract_type'])
        ->call('updateInternalPerson');

    $component->assertSet('activeModal', null)
        ->assertHasNoErrors()
        ->assertStatus(200);

    $this->assertDatabaseHas('internal_people', $newData);
});

it('validates required fields when updating', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $person = Person::factory()->create();
    $internalPerson = InternalPerson::factory()->create([
        'person_id' => $person->id
    ]);

    $component = Livewire::actingAs($user)
        ->test(InternalPersonTable::class)
        ->call('openModal', 'editInternalPerson', $internalPerson->id)
        ->set('email', '')
        ->set('phone', '')
        ->set('address', '')
        ->set('country', '')
        ->set('city', '')
        ->set('zip_code', '')
        ->set('contract_type', '')
        ->call('updateInternalPerson');

    $component->assertHasErrors([
        'email',
        'phone',
        'address',
        'country',
        'city',
        'zip_code',
        'contract_type'
    ]);
});