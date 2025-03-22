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

function openLightbox(src, type) {
    const lightbox = document.getElementById("lightbox");
    const img = document.getElementById("lightbox-img");
    const video = document.getElementById("lightbox-video");

    lightbox.style.display = "flex";

    if (type === "image") {
        img.src = src;
        img.style.display = "block";
        video.style.display = "none";
    } else if (type === "video") {
        video.src = src;
        video.style.display = "block";
        img.style.display = "none";
    }
}

// Open Lightbox
function openLightbox(src, type) {
    const lightbox = document.getElementById("lightbox");
    const img = document.getElementById("lightbox-img");
    const video = document.getElementById("lightbox-video");

    lightbox.style.display = "flex";

    if (type === "image") {
        img.src = src;
        img.style.display = "block";
        video.style.display = "none";
    } else if (type === "video") {
        video.src = src;
        video.style.display = "block";
        img.style.display = "none";
    }
}

// Close Lightbox
function closeLightbox() {
    const lightbox = document.getElementById("lightbox");
    const video = document.getElementById("lightbox-video");

    lightbox.style.display = "none";
    video.pause();
    video.currentTime = 0;
}

// Close Lightbox on Click Outside
document.getElementById("lightbox").addEventListener("click", function (e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close Lightbox with Escape Key
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeLightbox();
    }
});

let currentMedia = null; // Stores the currently playing media (audio/video)

function handleMediaPlayback(mediaElement) {
    // Pause any currently playing media before playing a new one
    if (currentMedia && currentMedia !== mediaElement) {
        currentMedia.pause();
        if (currentMedia.tagName === "VIDEO") {
            currentMedia.currentTime = 0; // Reset video to start
        }
    }
    currentMedia = mediaElement; // Update current playing media
}

// Select all audio elements
const audios = document.querySelectorAll("audio");
audios.forEach(audio => {
    audio.addEventListener("play", function () {
        handleMediaPlayback(this);
    });
});

// Select all video elements
const videos = document.querySelectorAll("video");
videos.forEach(video => {
    video.addEventListener("play", function () {
        handleMediaPlayback(this);
    });
});
window.addEventListener("scroll", activeMenu);
