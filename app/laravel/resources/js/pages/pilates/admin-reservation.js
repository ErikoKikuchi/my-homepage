const adminCalendar = document.getElementById("admin-calendar");

let currentData = null;
document
    .getElementById("prev-week-btn")
    .addEventListener("click", () => loadWeek(currentData.previous));
document
    .getElementById("next-week-btn")
    .addEventListener("click", () => loadWeek(currentData.next));

const week = document.getElementById("admin-calendar").dataset.week;
loadWeek(week);

async function loadWeek(week) {
    const response = await fetch(
        `/pilates/admin/calendar/events?week_start=${week}`,
        {
            headers: { Accept: "application/json" },
        },
    );
    currentData = await response.json();
    renderWeeklyCalendar();
}

function renderWeeklyCalendar() {
    const datesGrid = adminCalendar.querySelector(".dates");
    datesGrid.innerHTML = "";
    const start = new Date(currentData.weekStart);

    for (let i = 0; i < 7; i++) {
        const date = new Date(start);
        date.setDate(start.getDate() + i);
        const dateString = date.toISOString().slice(0, 10);
        console.log(
            "生成したdateString:",
            dateString,
            "存在するか:",
            !!currentData.weekMap[dateString],
        ); // "YYYY-MM-DD"

        const dayCell = document.createElement("div");
        dayCell.classList.add("day-cell");

        const slots = currentData.weekMap[dateString];

        if (slots) {
            const dateLabel = document.createElement("div");
            dateLabel.textContent = dateString;
            dayCell.appendChild(dateLabel);

            slots.forEach((slot) => {
                dayCell.appendChild(renderSlotBlock(slot));
            });
        }
        // slotsがなければ dayCell は空のまま(位置だけ確保)

        datesGrid.appendChild(dayCell);
    }
}

function renderSlotBlock(slot) {
    const block = document.createElement("div");
    block.className =
        "flex flex-col gap-1 p-2 mb-1 border border-forest-light rounded bg-paper";

    // 1. 日付・時間
    const timeLabel = document.createElement("div");
    timeLabel.className = "font-bold text-forest-dark";
    timeLabel.textContent = slot.time;
    block.appendChild(timeLabel);

    const hasReservation = slot.reservations.length > 0;
    // 2. 名前 or 予約ボタン
    const nameRow = document.createElement("div");
    nameRow.className = "text-sm";

    if (hasReservation) {
        nameRow.textContent = slot.reservations[0].name;
    } else {
        const reserveBtn = document.createElement("button");
        reserveBtn.textContent = "予約";
        reserveBtn.className = "btn btn-outline btn-sm";
        reserveBtn.addEventListener("click", () => goToReservationCreate(slot));
        nameRow.appendChild(reserveBtn);
    }
    block.appendChild(nameRow);

    const locationRow = document.createElement("div");
    locationRow.className = "text-sm";
    locationRow.textContent = slot.location ? slot.location.name : "";
    block.appendChild(locationRow);

    if (hasReservation) {
        const detailBtn = document.createElement("button");
        detailBtn.textContent = "詳細";
        detailBtn.className = "btn btn-primary btn-sm";
        detailBtn.addEventListener("click", () => showSlotDetail(slot));
        block.appendChild(detailBtn);
    }

    return block;
}
function goToReservationCreate(slot) {
    window.location.href = `/pilates/admin/lesson-slots/${slot.id}/reservations/create`;
}
