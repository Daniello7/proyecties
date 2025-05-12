<?php

namespace App\Livewire;

use Closure;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Sidebar extends Component
{
    public ?int $unreadPdfCount = null;
    protected $listeners = [
        'pdfGenerated' => 'loadUnreadPdfCount',
        'updated-pdf' => 'loadUnreadPdfCount',
    ];

    public function mount()
    {
        $this->loadUnreadPdfCount();
    }

    public function loadUnreadPdfCount(): void
    {
        if (auth()->check()) {
            $this->unreadPdfCount = auth()->user()
                ->pdfExports()
                ->whereNull('viewed_at')
                ->count();
        }
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
            ['name' => 'PDF', 'url' => 'pdf-exports'],
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

    public function render(): View|Closure|string
    {
        return view('livewire.sidebar', ['links' => $this->getLinks()]);
    }
}
