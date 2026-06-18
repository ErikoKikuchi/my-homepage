let selectedTime = null;
let firstPlace = null;
let secondPlace = null;
let dateString = null;
let participants = null;
let participantsName = null;
let note = null;

document.querySelectorAll(".time-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        // 全ボタンのハイライトをリセット
        document
            .querySelectorAll(".time-btn")
            .forEach((b) =>
                b.classList.remove(
                    "selected",
                    "bg-forest-dark",
                    "ring-2",
                    "ring-forest",
                ),
            );
        // クリックしたボタンをハイライト
        btn.classList.add(
            "selected",
            "bg-forest-dark",
            "ring-2",
            "ring-forest",
        );
    });
});

document.getElementById("reservation-confirm").addEventListener("click", () => {
    dateString = document.querySelector(".reservation-form").dataset.date;
    const isAuthenticated =
        document.querySelector(".reservation-form").dataset.authenticated ===
        "true";
    const loginUrl =
        document.querySelector(".reservation-form").dataset.loginUrl;

    if (!isAuthenticated) {
        window.location.href = `${loginUrl}?from=pilates-create&date=${dateString}`;
        return;
    }

    selectedTime = document.querySelector(".time-btn.selected")?.dataset.time;
    firstPlace = document.getElementById("first-place").value;
    secondPlace = document.getElementById("second-place")?.value ?? null;
    participants = document.getElementById("participants").value;
    participantsName =
        document.getElementById("participants-name")?.value ?? null;
    note = document.getElementById("note")?.value ?? null;

    const firstPlaceName =
        document.getElementById("first-place").selectedOptions[0].text;

    const secondPlaceName =
        document.getElementById("second-place")?.selectedOptions[0].text;

    document.getElementById("modal-date").textContent = dateString;
    document.getElementById("modal-time").textContent = selectedTime;
    document.getElementById("modal-place").textContent = firstPlaceName;
    document.getElementById("modal-place2").textContent = secondPlaceName;
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
                first_place: firstPlace,
                second_place: secondPlace,
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
