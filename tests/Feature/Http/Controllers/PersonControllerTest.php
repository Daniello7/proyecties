<?php

use App\Models\Person;

beforeEach(function () {
    actingAsPorter();
});

it('stores a person successfully', function () {
    // Arrange
    $data = [
        'name' => 'John',
        'last_name' => 'Doe',
        'document_number' => '1234567890',
        'company' => 'Example Corp',
        'comment' => 'A new person entry.',
    ];

    // Act
    $response = $this->post(route('person.store'), $data);

    // Assert
    $response->assertRedirect(route('person-entries'));
    $response->assertSessionHas('status', 'Person created successfully');
    $this->assertDatabaseHas('people', [
        'name' => 'John',
        'last_name' => 'Doe',
        'document_number' => '1234567890',
        'company' => 'Example Corp',
        'comment' => 'A new person entry.',
    ]);
});

it('updates a person successfully', function () {
    // Arrange
    $person = Person::factory()->create();
    $data = [
        'name' => 'Jane',
        'last_name' => 'Doe',
        'document_number' => '9876543210',
        'company' => 'New Corp',
        'comment' => 'Updated comment.',
    ];

    // Act
    $response = $this->put(route('person.update', $person->id), $data);

    // Assert
    $response->assertRedirect(route('person-entries'));
    $response->assertSessionHas('status', 'Person updated successfully');
    $this->assertDatabaseHas('people', [
        'name' => 'Jane',
        'last_name' => 'Doe',
        'document_number' => '9876543210',
        'company' => 'New Corp',
        'comment' => 'Updated comment.',
    ]);
});

it('shows a person successfully', function () {
    // Arrange
    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('person.show', $person->id));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('person.show');
    $response->assertViewHas('person', $person);
});

it('returns a 404 if the person does not exist', function () {
    // Arrange
    $nonExistentId = 999;

    // Act
    $response = $this->get(route('person.show', $nonExistentId));

    // Assert
    $response->assertStatus(404);
});

it('renders the person create page successfully', function () {
    // Arrange
    // No specific data is needed for this test

    // Act
    $response = $this->get(route('person.create'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('person.create');
});

it('renders the person edit page successfully', function () {
    // Arrange
    $person = Person::factory()->create();

    // Act
    $response = $this->get(route('person.edit', $person->id));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('person.edit');
    $response->assertViewHas('person', $person);
});
