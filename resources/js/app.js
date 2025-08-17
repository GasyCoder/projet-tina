import './bootstrap';

import Alpine from 'alpinejs';
import { registerSW } from 'virtual:pwa-register'

registerSW({ immediate: true })   // géré par vite-plugin-pwa

window.Alpine = Alpine;

Alpine.start();
