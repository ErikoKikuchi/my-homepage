// 1. メタ情報からCSRFトークンを取得する（例: XSRF-TOKEN という名前の場合）
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

//LineLinked
const lineLinked = document.querySelector('[data-field="line_linked"]');
const patchUrl = lineLinked.dataset.patchUrl;
lineLinked.addEventListener("change", async (event) => {
    try {
        const value = event.target.checked;
        const response = await fetch(patchUrl, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken, // ← ここでトークンを渡す
            },
            body: JSON.stringify({ line_linked: value }),
        });

        if (!response.ok) {
            event.target.checked = !value;
        }
    } catch (error) {
        console.error("エラー：", error);
    }
});
//Active
const isActive = document.querySelector('[data-field="is_active"]');
const activePatchUrl = isActive.dataset.patchUrl;
isActive.addEventListener("change", async (event) => {
    try {
        const value = event.target.checked;
        if (!value) {
            const confirmResult = confirm(isActive.dataset.confirmMessage);

            if (!confirmResult) {
                event.target.checked = !value;
                return;
            }
        }

        const response = await fetch(activePatchUrl, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken, // ← ここでトークンを渡す
            },
            body: JSON.stringify({ is_active: value }),
        });
        if (!response.ok) {
            event.target.checked = !value;
        }
    } catch (error) {
        console.error("エラー：", error);
    }
});
