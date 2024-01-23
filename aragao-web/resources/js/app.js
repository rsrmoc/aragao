import './bootstrap';
import './alpine';

window.initLoading = () => {
    document.querySelector('div.app-loading').classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('div.app-loading').classList.add('hidden');
})