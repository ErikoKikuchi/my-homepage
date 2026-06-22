let dateString = null;
let participants = null;
let participantsName = null;
let note = null;
let selectedTime = null;

document.getElementById("reservation-confirm").addEventListener("click", () => {
    dateString = document.querySelector(".reservation-form").dataset.date;
    selectedTime = document.querySelector(".reservation-form").dataset.time;
    const isAuthenticated =
        document.querySelector(".reservation-form").dataset.authenticated ===
        "true";
    const loginUrl =
        document.querySelector(".reservation-form").dataset.loginUrl;

    if (!isAuthenticated) {
        window.location.href = `${loginUrl}?from=pilates-create&date=${dateString}`;
        return;
    }

    participants = document.getElementById("participants").value;
    participantsName =
        document.getElementById("participants_name")?.value ?? null;
    note = document.getElementById("note")?.value ?? null;

    document.getElementById("modal-date").textContent = dateString;
    document.getElementById("modal-time").textContent = selectedTime;
    document.getElementById("modal-participants").textContent = participants;
    document.getElementById("modal-participants-name").textContent =
        participantsName;
    document.getElementById("modal-note").textContent = note;
    document.getElementById("confirm-modal").classList.remove("hidden");
});

// キャンセル
document.getElementById("modal-cancel").addEventListener("click", () => {
    document.getElementById("confirm-modal").classList.add("hidden");
});

// 予約する
document.getElementById("modal-submit").addEventListener("click", async () => {
    try {
        console.log("クリックされた");
        const response = await fetch("/pilates/reservations", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                date: dateString,
                time: selectedTime,
                participants: participants,
                participants_name: participantsName,
                note: note,
            }),
        });
        if (response.ok) {
            const data = await response.json();
            // モーダルを閉じる
            document.getElementById("confirm-modal").classList.add("hidden");
            // マイページへリダイレクト
            window.location.href = "/pilates/mypage";
        } else {
            const error = await response.json();
            console.error("エラー：", error);
        }
    } catch (error) {
        console.error("エラー：", error);
    }
});
