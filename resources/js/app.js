import "./bootstrap";

/** Close mobile header drawer when a link is chosen (frontend). */
const mobileNav = document.getElementById("siteMobileNav");
if (mobileNav) {
    mobileNav.querySelectorAll("a[href]").forEach((link) => {
        link.addEventListener("click", () => {
            mobileNav.open = false;
        });
    });
}

/**
 * Desktop submenu: keyboard only (.site-desktop-nav__item--open).
 * Mouse uses CSS :hover — no JS, so click+focus on parent link does not leave the panel stuck open.
 */
function initDesktopNavDropdowns() {
    const mq = window.matchMedia("(min-width: 1024px)");
    const items = document.querySelectorAll(".site-desktop-nav__item");
    if (items.length === 0) {
        return;
    }

    items.forEach((item) => {
        item.addEventListener("focusin", () => {
            if (!mq.matches) {
                return;
            }
            item.classList.add("site-desktop-nav__item--open");
        });
        item.addEventListener("focusout", (e) => {
            const next = e.relatedTarget;
            if (next instanceof Node && item.contains(next)) {
                return;
            }
            item.classList.remove("site-desktop-nav__item--open");
        });
    });

    document.addEventListener("keydown", (e) => {
        if (e.key !== "Escape") {
            return;
        }
        items.forEach((item) => item.classList.remove("site-desktop-nav__item--open"));
    });

    mq.addEventListener("change", () => {
        if (!mq.matches) {
            items.forEach((item) => item.classList.remove("site-desktop-nav__item--open"));
        }
    });
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDesktopNavDropdowns);
} else {
    initDesktopNavDropdowns();
}
