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
    const response = await fetch(`?week_start=${week}`, {
        headers: { Accept: "application/json" },
    });
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
    block.classList.add("slot-block", `status-${slot.status}`);

    const timeLabel = document.createElement("span");
    timeLabel.textContent = slot.time;
    block.appendChild(timeLabel);

    if (slot.reservations.length > 0) {
        const nameLabel = document.createElement("span");
        nameLabel.textContent = slot.reservations[0].name;
        block.appendChild(nameLabel);
    }

    const detailBtn = document.createElement("button");
    detailBtn.textContent = "詳細";
    detailBtn.addEventListener("click", () => showSlotDetail(slot));
    block.appendChild(detailBtn);

    return block;
}
