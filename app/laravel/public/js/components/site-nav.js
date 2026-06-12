// public/js/components/site-nav.js
const staticUrl = window.STATIC_URL ?? "http://localhost:5173";

class SiteNav extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <nav class="site-nav">
                <a href="${staticUrl}/" class="logo">からだ散歩</a>
                <ul class="nav-links">
                    <li><a href="${staticUrl}/src/about/index.html">About</a></li>
                    <li><a href="${staticUrl}/src/pilates/index.html">Pilates</a></li>
                    <li><a href="/">ThinkMotion</a></li>
                    <li><a href="${staticUrl}/src/coming-soon/index.html">Code</a></li>
                    <li><a href="${staticUrl}/src/contact/index.html">Contact</a></li>
                </ul>
            </nav>
        `;
        const nav = this.querySelector(".site-nav");
        const updateHeight = () => {
            document.documentElement.style.setProperty(
                "--site-nav-height",
                `${nav.offsetHeight}px`,
            );
        };

        updateHeight();
        new ResizeObserver(updateHeight).observe(nav);
    }
}
customElements.define("site-nav", SiteNav);
