import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.addEventListener('load', () => {
    const loader = document.getElementById('loader');
    const content = document.getElementById('content');

    if (loader) {
        loader.style.transition = 'opacity 0.8s ease';
        loader.style.opacity = '0';

        window.setTimeout(() => {
            loader.style.display = 'none';
        }, 800);
    }

    if (content) {
        content.style.transition = 'opacity 1s ease, transform 1s ease';
        content.style.opacity = '1';
        content.style.transform = 'translateY(-20px)';
    }
});
