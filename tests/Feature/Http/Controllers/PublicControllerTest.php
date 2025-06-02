<?php

it('renders welcome view', function () {
    // Act
    $response = $this->get(route('welcome'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('welcome');
});

it('renders about view', function () {
    // Act
    $response = $this->get(route('about'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('about');
});

it('renders contact view', function () {
    // Act
    $response = $this->get(route('contact'));

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('contact');
});