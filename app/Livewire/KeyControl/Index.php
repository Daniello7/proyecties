<?php

namespace App\Livewire\KeyControl;

use Livewire\Component;

class Index extends Component
{
    public bool $openedExitKeysTable = true;
    public bool $openedCreateExitKey = false;
    public bool $openedSearchKey = false;

    public function openExitKeysTable(): void
    {
        $this->closeWindows();
        $this->openedExitKeysTable = true;
    }

    public function openCreateExitKey(): void
    {
        $this->closeWindows();
        $this->openedCreateExitKey = true;
    }

    public function openSearchKey(): void
    {
        $this->closeWindows();
        $this->openedSearchKey = true;
    }

    private function closeWindows(): void
    {
        $this->openedExitKeysTable = false;
        $this->openedCreateExitKey = false;
        $this->openedSearchKey = false;
    }

    public function storeExitKey()
    {

    }

    public function render()
    {
        return view('livewire.key-control.index');
    }
}
