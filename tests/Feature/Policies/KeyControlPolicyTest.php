<?php

use App\Models\User;
use App\Policies\KeyControlPolicy;

beforeEach(function () {
    $this->policy = new KeyControlPolicy();
});

describe('viewAny', function () {
    it('allows administrators to view', function () {
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->viewAny($admin))->toBeTrue();
    });

    it('allows porters to view', function () {
        $porter = Mockery::mock(User::class);
        $porter->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->viewAny($porter))->toBeTrue();
    });

    it('denies view to other roles', function () {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(false);
        
        expect($this->policy->viewAny($user))->toBeFalse();
    });
});

describe('create', function () {
    it('allows only porters to create', function () {
        $porter = Mockery::mock(User::class);
        $porter->shouldReceive('hasRole')->with('porter')->once()->andReturn(true);
        
        expect($this->policy->create($porter))->toBeTrue();
    });

    it('denies administrators to create', function () {
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('hasRole')->with('porter')->once()->andReturn(false);
        
        expect($this->policy->create($admin))->toBeFalse();
    });
});

describe('update', function () {
    it('allows administrators to update', function () {
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->update($admin))->toBeTrue();
    });

    it('allows porters to update', function () {
        $porter = Mockery::mock(User::class);
        $porter->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->update($porter))->toBeTrue();
    });

    it('denies update to other roles', function () {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(false);
        
        expect($this->policy->update($user))->toBeFalse();
    });
});

describe('delete', function () {
    it('allows only administrators to delete', function () {
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('hasRole')->with('admin')->once()->andReturn(true);
        
        expect($this->policy->delete($admin))->toBeTrue();
    });

    it('denies porters to delete', function () {
        $porter = Mockery::mock(User::class);
        $porter->shouldReceive('hasRole')->with('admin')->once()->andReturn(false);
        
        expect($this->policy->delete($porter))->toBeFalse();
    });
});

describe('cancel', function () {
    it('allows administrators to cancel', function () {
        $admin = Mockery::mock(User::class);
        $admin->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->cancel($admin))->toBeTrue();
    });

    it('allows porters to cancel', function () {
        $porter = Mockery::mock(User::class);
        $porter->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(true);
        
        expect($this->policy->cancel($porter))->toBeTrue();
    });

    it('denies cancel to other roles', function () {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('hasRole')->with(['admin', 'porter'])->once()->andReturn(false);
        
        expect($this->policy->cancel($user))->toBeFalse();
    });
});