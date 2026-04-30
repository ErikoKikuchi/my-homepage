import { THINKMOTION_URL } from '../constants/urls';
//ナビ共通コンポーネント

class SiteNav extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <nav class="site-nav">
                <a href="/" class="logo">からだ散歩</a>
                <ul class="nav-links">
                <li><a href="../about/index.html">About</a></li>
                <li><a href="../pilates/index.html">Pilates</a></li>
                <li><a href="${THINKMOTION_URL}/thinkmotion">ThinkMotion</a></li>
                <li><a href="../coming-soon/index.html">Code</a></li>
                <li><a href="../contact/index.html">Contact</a></li>
                </ul>
            </nav>
        `;
    }
}
customElements.define('site-nav', SiteNav);