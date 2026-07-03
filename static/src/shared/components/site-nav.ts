import { THINKMOTION_URL } from "../constants/urls";
import { PILATES_URL } from "../constants/urls";
//ナビ共通コンポーネント

class SiteNav extends HTMLElement {
  private resizeObserver?: ResizeObserver;

  connectedCallback() {
    this.innerHTML = `
            <nav class="site-nav">
                <a href="/" class="logo">からだ散歩</a>
                <ul class="nav-links">
                <li><a href="../about/index.html">About</a></li>
                <li><a href="${PILATES_URL}/pilates">Pilates</a></li>
                <li><a href="${THINKMOTION_URL}/thinkmotion">ThinkMotion</a></li>
                <li><a href="../coming-soon/index.html">Code</a></li>
                <li><a href="../contact/index.html">Contact</a></li>
                </ul>
            </nav>
        `;

    this.syncHeight();
    this.resizeObserver = new ResizeObserver(() => this.syncHeight());
    this.resizeObserver.observe(this);
  }

  disconnectedCallback() {
    this.resizeObserver?.disconnect();
  }

  private syncHeight() {
    const nav = this.querySelector(".site-nav");
    const height = nav ? nav.getBoundingClientRect().height : 0;
    document.documentElement.style.setProperty(
      "--site-nav-height",
      `${height}px`,
    );
  }
}
customElements.define("site-nav", SiteNav);
