<?php

namespace App\Livewire;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Sidebar extends Component
{
    public ?int $unreadPdfCount = null;
    public $currentRoute = '';

    protected $listeners = [
        'documentGenerated' => 'loadUnreadDocumentCount',
        'updated-document' => 'loadUnreadDocumentCount',
    ];

    public function mount()
    {
        $this->loadUnreadDocumentCount();
        $this->currentRoute = Route::currentRouteName();
    }

    public function loadUnreadDocumentCount(): void
    {
        if (auth()->check()) {
            $this->unreadPdfCount = auth()->user()
                ->documentExports()
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
            return $this->getAdminLinks();

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
            ['name' => 'Documents', 'url' => 'document-exports'],
            ['name' => 'Dashboard', 'url' => 'dashboard'],
        ];
    }

    public function getHRLinks(): array
    {
        return [
            ['name' => 'Human Resources', 'url' => 'human-resources'],
            ['name' => 'External Staff', 'url' => 'person-entries'],
            ['name' => 'Internal Staff', 'url' => 'internal-person'],
            ['name' => 'Dashboard', 'url' => 'dashboard'],
        ];
    }

    public function getAdminLinks(): array
    {
        return [
            ['name' => 'Admin', 'url' => 'admin'],
            ['name' => 'External Staff', 'url' => 'person-entries'],
            ['name' => 'Internal Staff', 'url' => 'internal-person'],
            ['name' => 'Package', 'url' => 'packages'],
            ['name' => 'Key Control', 'url' => 'key-control'],
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

    public function isActiveLink(string $url): bool
    {
        return str_starts_with($this->currentRoute, $url);
    }


    public function render(): View|Closure|string
    {
        return view('livewire.sidebar', ['links' => $this->getLinks()]);
    }
}
