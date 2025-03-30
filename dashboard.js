// Mobile menu functionality
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector(".nav-menu");
    
    hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("active");
        navMenu.classList.toggle("active");
    });
    
    document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    }));
    
    // Dark mode toggle
    const toggleSwitch = document.querySelector(".input");
    const body = document.body;

    // Check for saved user preference in localStorage
    const currentTheme = localStorage.getItem("theme");

    if (currentTheme === "dark") {
        body.classList.add("dark-mode");
        toggleSwitch.checked = true; // Set toggle switch to dark mode
    }

    toggleSwitch.addEventListener("change", () => {
        if (toggleSwitch.checked) {
            body.classList.add("dark-mode");
            localStorage.setItem("theme", "dark");
        } else {
            body.classList.remove("dark-mode");
            localStorage.setItem("theme", "light");
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        const chatContainer = document.getElementById("chatContainer");
        chatContainer.style.display = "none";
    });
    