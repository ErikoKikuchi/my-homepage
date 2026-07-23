const button = document.querySelector("[data-client-register-modal]");
const modal = document.getElementById("client-register-modal");
const cancelBtn = document.getElementById("client-modal-cancel");
const submitBtn = document.getElementById("client-modal-submit");

let currentUserId = null;

button.addEventListener("click", () => {
    currentUserId = button.dataset.userId;
    document.getElementById("modal-user-name").textContent =
        button.dataset.userName;
    modal.classList.remove("hidden");
});

// キャンセル
cancelBtn.addEventListener("click", () => {
    modal.classList.add("hidden");
});

// 予約する
submitBtn.addEventListener("click", async () => {
    try {
        const checkedGender = document.querySelector(
            'input[name="gender"]:checked',
        );
        if (!checkedGender) {
            // 何も選ばれていない場合の処理(エラー表示など)
            const errorEl = document.getElementById("modal-gender-error");
            errorEl.textContent = "性別を選択してください。";
            errorEl.classList.remove("hidden");

            return;
        }

        const gender = checkedGender.value; // 'female' | 'male' | 'other'

        const response = await fetch("/pilates/admin/clients", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                user_id: currentUserId,
                gender: gender,
            }),
        });
        if (response.ok) {
            const data = await response.json();
            // モーダルを閉じる
            document
                .getElementById("client-register-modal")
                .classList.add("hidden");
            // マイページへリダイレクト
            window.location.href = "/pilates/admin/clients";
        } else {
            const error = await response.json();
            console.error("エラー：", error);
            alert(JSON.stringify(error));
        }
    } catch (error) {
        console.error("エラー：", error);
    }
});
