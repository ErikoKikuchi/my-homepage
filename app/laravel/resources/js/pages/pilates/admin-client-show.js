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
                Accept: "application/json",
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
                Accept: "application/json",
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
//名前変更
const startEditButton = document.querySelector(".js-start-edit");
startEditButton.addEventListener("click", () => {
    const items = document.querySelector(".js-display");
    items.classList.add("hidden");
    const editForm = document.querySelector(".js-edit-form");
    editForm.classList.remove("hidden");
    editForm.classList.add("flex");
});
//キャンセル処理
const cancelButton = document.querySelector(".js-cancel-edit");
cancelButton.addEventListener("click", () => {
    const originalName =
        document.querySelector(".js-display-value").textContent;
    document.querySelector(".js-name-input").value = originalName;
    document.querySelector(".js-edit-form").classList.add("hidden");
    document.querySelector(".js-display").classList.remove("hidden");
});
//保存処理
const editForm = document.querySelector(".js-edit-form");
const namePatchUrl = editForm.dataset.patchUrl;
editForm.addEventListener("submit", async (event) => {
    try {
        event.preventDefault();
        const newName = document.querySelector(".js-name-input").value;
        const response = await fetch(namePatchUrl, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-Token": csrfToken, // ← ここでトークンを渡す
            },
            body: JSON.stringify({ name: newName }),
        });
        if (!response.ok) {
            const errorEl = document.querySelector(".js-name-error");
            errorEl.classList.remove("hidden");
            errorEl.textContent = "氏名を記入してください";
        } else {
            document.querySelector(".js-display-value").textContent = newName;
            document.querySelector(".js-edit-form").classList.add("hidden");
            document.querySelector(".js-display").classList.remove("hidden");
        }
    } catch (error) {
        console.error("エラー：", error);
    }
});
