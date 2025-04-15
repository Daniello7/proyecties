<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    public function __construct()
    {
    }

    public function getLinks(): array
    {
        if (!auth()->check()) {
            return $this->getGuestLinks();
        }

        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return [
                'admin' => $this->getAdminLinks(),
                'porter' => $this->getPorterLinks(),
                'rrhh' => $this->getHRLinks()
            ];
        } elseif ($user->hasRole('porter')) {
            return $this->getPorterLinks();

        } elseif ($user->hasRole('rrhh')) {
            return $this->getHRLinks();
        }

        return [];
    }

    public function getPorterLinks(): array
    {
        return [
            ['name' => 'Home', 'url' => 'control-access'],
            ['name' => 'External Staff', 'url' => 'person-entries'],
            ['name' => 'Internal Staff', 'url' => 'internal-person'],
            ['name' => 'Package', 'url' => 'packages'],
            ['name' => 'Key Control', 'url' => 'key-control'],
            ['name' => 'Dashboard', 'url' => 'dashboard'],
        ];
    }

    public function getHRLinks(): array
    {
        return [
            ['name' => 'HR', 'url' => 'welcome'],
            ['name' => 'External Staff', 'url' => 'person-entries'],
            ['name' => 'Internal Staff', 'url' => 'internal-person'],
            ['name' => 'Dashboard', 'url' => 'dashboard'],
        ];
    }

    public function getAdminLinks(): array
    {
        return [
            ['name' => 'Admin', 'url' => 'admin'],
            ['name' => 'Dashboard', 'url' => 'dashboard'],
        ];
    }

    public function getGuestLinks(): array
    {
        return [
            ['name' => 'Home', 'url' => 'welcome'],
            ['name' => 'About', 'url' => 'about'],
            ['name' => 'Contact', 'url' => 'contact'],
            ['name' => 'Log in', 'url' => 'login'],
            ['name' => 'Register', 'url' => 'register'],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar', ['links' => $this->getLinks()]);
    }
}
