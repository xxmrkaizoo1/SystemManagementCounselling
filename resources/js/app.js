import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('load', () => {
    // Home page loader animation
    const circle = document.getElementById('circle');
    const loader = document.getElementById('loader');
    const content = document.getElementById('content');
    const logoText = document.getElementById('logoText');

    if (circle && loader && content && logoText) {
        circle.style.transition = 'transform 1.2s ease-in-out';
        loader.style.transition = 'opacity 0.8s ease';
        logoText.style.transition = 'opacity 0.5s ease';

        setTimeout(() => {
            logoText.style.opacity = '0';
        }, 500);

        setTimeout(() => {
            circle.style.transform = 'scale(25)';
        }, 800);

        setTimeout(() => {
            loader.style.opacity = '0';
        }, 1600);

        setTimeout(() => {
            loader.style.display = 'none';
            content.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            content.style.opacity = '1';
            content.style.transform = 'translateY(0px)';
        }, 2200);
    }

    // Login page loader animation
    const loginLoader = document.getElementById('loginLoader');
    const loginContent = document.getElementById('loginContent');

    if (loginLoader && loginContent) {
        window.setTimeout(() => {
            loginLoader.style.opacity = '0';
        }, 500);

        window.setTimeout(() => {
            loginLoader.style.display = 'none';
            loginContent.classList.remove('opacity-0', 'translate-y-2');
            loginContent.classList.add('opacity-100', 'translate-y-0');
        }, 1150);
    }

    // Rotating doctor tips on index page
    const doctorTipText = document.getElementById('doctorTipText');

    if (doctorTipText) {
        const tips = [
            'Small daily check-ins with yourself can prevent stress from piling up.',
            'If anxiety feels heavy, try 4-7-8 breathing for one minute before class.',
            'You can ask for help early — booking support is a strength, not a weakness.',
            'A short walk and water break can quickly reset your focus and mood.',
            'Write three worries down, then choose one tiny action you can do today.',
        ];

        let tipIndex = 0;
        const tipLoopMs = 15000;

        window.setInterval(() => {
            tipIndex = (tipIndex + 1) % tips.length;
            doctorTipText.classList.remove('tip-swap');
            void doctorTipText.offsetWidth;
            doctorTipText.textContent = tips[tipIndex];
            doctorTipText.classList.add('tip-swap');
        }, tipLoopMs);
    }
});
