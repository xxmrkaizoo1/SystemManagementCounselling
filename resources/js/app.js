import './bootstrap';
import Alpine from 'alpinejs';
import { gsap } from "gsap";

window.Alpine = Alpine;
Alpine.start();

window.addEventListener("load", () => {

    gsap.to("#loader", {
        opacity: 0,
        duration: 0.8,
        onComplete: () => {
            document.getElementById("loader").style.display = "none";
        }
    });

    gsap.to("#content", {
        opacity: 1,
        y: -20,
        duration: 1,
        delay: 0.5
    });

});
