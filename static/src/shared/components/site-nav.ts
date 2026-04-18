//ナビ共通コンポーネント

class SiteNav extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <nav class="site-nav">
                <a href="/" class="logo">からだ散歩</a>
                <ul class="nav-links">
                <li><a href="../about/index.html">About</a></li>
                <li><a href="../pilates/index.html">Pilates</a></li>
                <li><a href="../think-motion/index.html">ThinkMotion</a></li>
                <li><a href="../code/index.html">Code</a></li>
                <li><a href="../contact/index.html">Contact</a></li>
                </ul>
            </nav>
        `;
    }
}
customElements.define('site-nav', SiteNav);