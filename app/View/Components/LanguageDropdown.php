<?php

namespace App\View\Components;

use Carbon\Language;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class LanguageDropdown extends Component
{
    public array $langKeys;
    public array $languages;

    public function __construct()
    {
        $this->langKeys = collect(File::directories(lang_path()))
            ->map(fn($dir) => basename($dir))
            ->toArray();

        $this->languages = Language::all();
    }

    public function render(): View|Closure|string
    {
        return view('components.language-dropdown');
    }
}
