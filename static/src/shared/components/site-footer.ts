//フッター共通コンポーネント

class SiteFooter extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <footer class="site-footer">
                <p>&copy; 2026 からだ散歩 / Eriko </p>
                <img src="/images/122060.png" alt="最下部の画像" class="bottom-image">
            </footer>
        `;
    }
}
customElements.define('site-footer', SiteFooter);