import './bootstrap';
import Alpine from 'alpinejs';
import { initializeTheme } from './theme';

window.Alpine = Alpine;
Alpine.start();

// Initialize theme when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    initializeTheme();
});
