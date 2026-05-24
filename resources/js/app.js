import "./bootstrap";

/** Mobile sidebar: smooth open/close, backdrop, scroll lock, escape */
const mobileNav = document.getElementById("siteMobileNav");
const mobileNavPanel = mobileNav?.querySelector(".site-mobile-nav__panel");
const mobileNavSummary = mobileNav?.querySelector(".site-mobile-nav__toggle");

const mobileNavDurationMs = 400;

function setMobileNavBodyLock(open) {
    document.body.classList.toggle("site-mobile-nav-open", open);
}

function finishMobileNavClose() {
    if (!mobileNav) {
        return;
    }
    mobileNav.classList.remove("site-mobile-nav--closing");
    mobileNav.open = false;
    setMobileNavBodyLock(false);
}

function closeMobileNavAnimated() {
    if (!mobileNav?.open || mobileNav.classList.contains("site-mobile-nav--closing")) {
        return;
    }

    mobileNav.classList.add("site-mobile-nav--closing");

    let finished = false;
    const finish = () => {
        if (finished) {
            return;
        }
        finished = true;
        mobileNavPanel?.removeEventListener("transitionend", onTransitionEnd);
        finishMobileNavClose();
    };

    const onTransitionEnd = (event) => {
        if (event.target !== mobileNavPanel || event.propertyName !== "transform") {
            return;
        }
        finish();
    };

    mobileNavPanel?.addEventListener("transitionend", onTransitionEnd);
    window.setTimeout(finish, mobileNavDurationMs + 80);
}

function openMobileNav() {
    if (!mobileNav) {
        return;
    }
    mobileNav.classList.remove("site-mobile-nav--closing");
    mobileNav.open = true;
    setMobileNavBodyLock(true);
}

if (mobileNav) {
    mobileNavSummary?.addEventListener("click", (event) => {
        if (mobileNav.open) {
            event.preventDefault();
            closeMobileNavAnimated();
        }
    });

    mobileNav.querySelectorAll("[data-mobile-nav-close]").forEach((el) => {
        el.addEventListener("click", (event) => {
            event.preventDefault();
            closeMobileNavAnimated();
        });
    });

    mobileNav.querySelectorAll("a[href]").forEach((link) => {
        link.addEventListener("click", () => closeMobileNavAnimated());
    });

    mobileNav.addEventListener("toggle", () => {
        if (mobileNav.open) {
            openMobileNav();
        } else if (!mobileNav.classList.contains("site-mobile-nav--closing")) {
            setMobileNavBodyLock(false);
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && mobileNav.open) {
            closeMobileNavAnimated();
        }
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

/** Service detail sidebar: accordion toggles (e.g. Technical Stores sub-menu) */
function initServiceDetailNav() {
    const nav = document.querySelector("[data-service-detail-nav]");
    if (!nav) {
        return;
    }

    nav.querySelectorAll("[data-service-nav-toggle]").forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const group = toggle.closest("[data-service-nav-group]");
            if (!group) {
                return;
            }

            const isOpen = group.classList.toggle("service-detail__nav-group--open");
            toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });
    });
}

function initSiteNav() {
    initDesktopNavDropdowns();
    initServiceDetailNav();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSiteNav);
} else {
    initSiteNav();
}
