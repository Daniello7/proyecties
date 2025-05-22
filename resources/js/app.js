import './bootstrap';
// import Alpine from 'alpinejs';
import {darkModeToggleButtonAction, initializeDarkMode} from './features/dark-mode.js';
import './features/reveal-scroll.js';

if (!window.Alpine) {
    import('alpinejs').then((module) => {
        window.Alpine = module.default;
        Alpine.start();
    });
}

// window.Alpine = Alpine;

// Alpine.start();

initializeDarkMode();
darkModeToggleButtonAction();
