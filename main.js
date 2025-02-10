// Menu on scroll
let header = document.querySelector('.header');
window.onscroll = function () {
    if (this.scrollY >= 100) {
        header.classList.add('active');
    } else {
        header.classList.remove('active');
    }
    btnMenu.classList.remove('fa-times');
    NavLinks.classList.remove('active');
};

let btnMenu = document.getElementById('btnMenu');
let NavLinks = document.querySelector('.nav-links');

// Menu toggle with delay
btnMenu.onclick = function () {
    btnMenu.classList.toggle('fa-times');

    if (!NavLinks.classList.contains("active")) {
        setTimeout(() => {
            NavLinks.classList.add("active");
        }, 200); // 200ms delay before showing
    } else {
        setTimeout(() => {
            NavLinks.classList.remove("active");
        }, 200); // 200ms delay before hiding
    }
};

// Smooth Scroll with Delay (Fixed Login Button Issue)
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener("click", function (e) {
        // Fix: Allow the Login button to work normally
        if (this.classList.contains("btn")) {
            window.open(this.href, "_blank"); // Open in new tab
            return; // Stop further execution
        }

        e.preventDefault(); // Prevent default for internal links
        let targetId = this.getAttribute("href");
        let targetSection = document.querySelector(targetId);

        if (targetSection) {
            setTimeout(() => {
                window.scrollTo({
                    top: targetSection.offsetTop - 50,
                    behavior: "smooth"
                });
            }, 300);
        }

        // Close menu after clicking
        setTimeout(() => {
            NavLinks.classList.remove("active");
            btnMenu.classList.remove("fa-times");
        }, 500);
    });
});

// Swiper Slider
var swiper = new Swiper(".swip-test-imo", {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

// Add class active to link in menu by scroll
let links = document.querySelectorAll('.nav-links a');
let sections = document.querySelectorAll('section');

function activeMenu() {
    let len = sections.length;
    while (--len && window.scrollY + 100 < sections[len].offsetTop) {}
    links.forEach(ltx => ltx.classList.remove("active"));
    links[len].classList.add("active");
}

window.addEventListener("scroll", activeMenu);
