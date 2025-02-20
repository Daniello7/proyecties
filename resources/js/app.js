import './bootstrap';
// import Alpine from 'alpinejs';
import {darkModeToggleButtonAction, initializeDarkMode} from './dark-mode.js';

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
