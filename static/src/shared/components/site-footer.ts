class SiteFooter extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
        <footer class="site-footer">
            <p class="copyright">© 2026 からだ散歩</p>
            <img src="/images/122060.png" class="bottom-image">
        </footer>
        `;
    }
}
customElements.define('site-footer', SiteFooter);