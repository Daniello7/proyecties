import './bootstrap';
import Alpine from 'alpinejs';
import {darkModeToggleButtonAction, initializeDarkMode} from './dark-mode.js';

window.Alpine = Alpine;

Alpine.start();

initializeDarkMode();
darkModeToggleButtonAction();
