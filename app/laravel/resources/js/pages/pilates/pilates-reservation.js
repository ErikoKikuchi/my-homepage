const calendar = document.getElementById("calendar");

let currentData = null;
document
    .getElementById("prev-month-btn")
    .addEventListener("click", () => loadMonth(currentData.previous));
document
    .getElementById("next-month-btn")
    .addEventListener("click", () => loadMonth(currentData.next));

const month = document.getElementById("calendar").dataset.month;
loadMonth(month);

function renderSchedule(date, dayOfWeek, times, status) {
    document.querySelector(".time-select h2").textContent =
        `${date}(${dayOfWeek})の空き状況`;
    const container = document.querySelector(
        ".time-select .flex.flex-col.items-start",
    );
    container.innerHTML = "";

    times.forEach((time) => {
        const row = document.createElement("div");
        row.className = "text-xl m-2 items-center flex";
        row.textContent = `${time.start} ～ ${time.end}`;
        const button = document.createElement("button");
        button.className = "time-btn text-xl m-1 text-white w-30 h-10 ";
        button.type = "button";
        button.dataset.time = JSON.stringify(time);
        button.textContent = "予約申請";

        if (status === "contact_only") {
            button.disabled = true;
            button.classList.add(
                "bg-gray-400",
                "cursor-not-allowed",
                "opacity-60",
            );
        } else {
            button.classList.add(
                "bg-forest",
                "cursor-pointer",
                "hover:bg-forest-dark",
            );
            button.addEventListener("click", () => {
                window.location.href = `/pilates/reservations/create?date=${date}&time=${time.start}`;
            });
        }
        row.appendChild(button);
        container.appendChild(row);
    });
    // contact_onlyの場合はQR案内を末尾に追加
    const existingNotice = document.querySelector(".contact-notice");
    if (existingNotice) existingNotice.remove();

    if (status === "contact_only") {
        const notice = document.createElement("div");
        notice.className = "contact-notice text-center m-4";
        notice.innerHTML = `
            <p class="mb-2">直前のご予約は下記のQRコードよりお問い合わせください。</p>
            <img src="/images/line-qr.png" alt="LINE QRコード" class="mx-auto w-32 h-32">
        `;
        container.parentElement.appendChild(notice);
    }
}

async function loadMonth(month) {
    const response = await fetch(`/pilates?month=${month}`, {
        headers: { Accept: "application/json" },
    });
    const data = await response.json();
    currentData = data;
    document.querySelector(".time-select").classList.add("hidden");
    renderCalendar(data.cells, data.month);

    function renderCalendar(cells, month) {
        document.querySelector("h2").textContent = `${month}月の空き状況`;
        const datesEl = document.querySelector(".dates");
        datesEl.innerHTML = ""; // 一旦datesクラスクリア

        cells.forEach((cell) => {
            const div = document.createElement("div");
            div.className =
                "date border border-accent items-center text-center flex flex-col text-xl ";
            if (cell === null) {
                div.textContent = "";
                div.className = "date border border-accent";
            } else {
                const dateString = `${month}-${String(cell.date).padStart(2, "0")}`;
                const dateEl = document.createElement("span");
                dateEl.textContent = cell.date;
                div.appendChild(dateEl);
                div.className =
                    "date border border-accent items-center text-center flex flex-col text-xl cursor-pointer hover:bg-accent ";

                if (cell.status !== null) {
                    const statusEl = document.createElement("span");
                    const statusIcons = {
                        available: "〇",
                        contact_only: "△",
                        full: "☓",
                    };
                    statusEl.textContent = statusIcons[cell.status] ?? "☓";
                    div.appendChild(statusEl);
                }
                if (cell.status === "full") {
                    // クリック無効・見た目も変える
                    div.classList.remove("cursor-pointer", "hover:bg-accent");
                    div.classList.add("opacity-50", "cursor-not-allowed");
                    div.addEventListener("click", (e) => e.preventDefault());
                } else if (
                    cell.status === "available" ||
                    cell.status === "contact_only"
                ) {
                    if (cell.status === "contact_only") {
                        div.classList.add("text-amber-600");
                    }
                    div.addEventListener("click", async () => {
                        dateColorEls.forEach((el) =>
                            el.classList.remove("selected", "bg-accent"),
                        );
                        div.classList.add("selected", "bg-accent");
                        const response = await fetch(
                            `/pilates/slots?date=${dateString}`,
                        );
                        const data = await response.json();
                        const timeSelect =
                            document.querySelector(".time-select");
                        timeSelect.classList.remove("hidden");
                        renderSchedule(
                            data.date,
                            data.dayOfWeek,
                            data.times,
                            cell.status,
                        );
                    });
                } else {
                    // status === null はクリック無効
                    div.classList.remove("cursor-pointer", "hover:bg-accent");
                }
            }
            datesEl.appendChild(div);
        });
        const dateColorEls = datesEl.querySelectorAll(".date");
    }
    document
        .getElementById("next-month-btn")
        .addEventListener("click", () => loadMonth(currentData.next));
}
