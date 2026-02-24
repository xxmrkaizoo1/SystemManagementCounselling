import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();



window.addEventListener("load", () => {

    const circle = document.getElementById("circle");
    const loader = document.getElementById("loader");
    const content = document.getElementById("content");
    const logoText = document.getElementById("logoText");

    circle.style.transition = "transform 1.2s ease-in-out";
    loader.style.transition = "opacity 0.8s ease";
    logoText.style.transition = "opacity 0.5s ease";

    // 1️⃣ Fade text first
    setTimeout(() => {
        logoText.style.opacity = "0";
    }, 500);

    // 2️⃣ Then zoom circle
    setTimeout(() => {
        circle.style.transform = "scale(25)";
    }, 800);

    // 3️⃣ Fade out loader
    setTimeout(() => {
        loader.style.opacity = "0";
    }, 1600);

    // 4️⃣ Show page
    setTimeout(() => {
        loader.style.display = "none";
        content.style.transition = "opacity 0.8s ease, transform 0.8s ease";
        content.style.opacity = "1";
        content.style.transform = "translateY(0px)";
    }, 2200);

});


// window.addEventListener("load", () => {
//   const loader = document.getElementById("loader");
//   const content = document.getElementById("content");
//   const bar = document.getElementById("bar");

//   // smooth show content
//   content.style.transition = "opacity 0.8s ease, transform 0.8s ease";
//   content.style.transform = "translateY(10px)";

//   // progress animation
//   let progress = 0;
//   const timer = setInterval(() => {
//     progress += 5;
//     bar.style.width = progress + "%";

//     if (progress >= 100) {
//       clearInterval(timer);

//       // fade out loader
//       loader.style.transition = "opacity 0.6s ease";
//       loader.style.opacity = "0";

//       setTimeout(() => {
//         loader.style.display = "none";
//         content.style.opacity = "1";
//         content.style.transform = "translateY(0px)";
//       }, 650);
//     }
//   }, 1000); // <-- speed (80ms) | make bigger = slower
// });
