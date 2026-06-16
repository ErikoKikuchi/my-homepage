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
    selectedTime = document.querySelector(".time-btn.selected")?.dataset.time;
    firstPlace = document.getElementById("first-place").value;
    secondPlace = document.getElementById("second-place")?.value ?? null;
    dateString = document.querySelector(".reservation-form").dataset.date;
    participants = document.getElementById("participants").value;
    participantsName =
        document.getElementById("participants-name")?.value ?? null;
    note = document.getElementById("note")?.value ?? null;

    const placeNames = {
        1: "自宅（未定）",
        2: "遠浅公民館",
        3: "早来スポーツセンター",
        4: "町内会館",
        5: "beauty Ruby",
    };

    document.getElementById("modal-date").textContent = dateString;
    document.getElementById("modal-time").textContent = selectedTime;
    document.getElementById("modal-place").textContent = placeNames[firstPlace];
    document.getElementById("modal-place2").textContent =
        placeNames[secondPlace];
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
        console.log(response.status);
        const data = await response.text();
        console.log(data); // ← 追加
    } catch (error) {
        console.error("エラー：", error);
    }
});
