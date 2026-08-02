import * as bootstrap from "bootstrap";

window.bootstrap = bootstrap;

document.querySelectorAll("[data-mobile-menu-toggle]").forEach((button) => {
    button.addEventListener("click", () => window.toggleMobileMenu?.());
});

document.querySelector("[data-mobile-destinations-toggle]")?.addEventListener("click", () => {
    window.toggleMobileDestinations?.();
});

document.querySelector("[data-mobile-language-toggle]")?.addEventListener("click", () => {
    window.toggleMobileLanguage?.();
});

document.querySelectorAll(".choose-card").forEach((card) => {
    card.addEventListener("mouseenter", () => {
        card.style.borderColor = "var(--rich-gold)";
        card.style.transform = "translateY(-8px)";
        card.style.boxShadow = "var(--shadow-dramatic)";
    });
    card.addEventListener("mouseleave", () => {
        card.style.borderColor = "transparent";
        card.style.transform = "translateY(0)";
        card.style.boxShadow = "var(--shadow-medium)";
    });
});
