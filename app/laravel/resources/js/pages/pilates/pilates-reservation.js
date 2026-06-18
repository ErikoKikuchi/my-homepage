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

async function loadMonth(month) {
    const response = await fetch(`/pilates?month=${month}`, {
        headers: { Accept: "application/json" },
    });
    const data = await response.json();
    currentData = data;
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
                    statusEl.textContent =
                        cell.status === "available" ? "〇" : "☓";
                    div.appendChild(statusEl);
                }
                if (cell.status === "full") {
                    // クリック無効・見た目も変える
                    div.classList.remove("cursor-pointer", "hover:bg-accent");
                    div.classList.add("opacity-50", "cursor-not-allowed");
                    div.addEventListener("click", (e) => e.preventDefault());
                } else if (cell.status === "available") {
                    div.addEventListener("click", () => {
                        window.location.href = `/pilates/reservations/create?date=${dateString}`;
                    });
                } else {
                    // status === null はクリック無効
                    div.classList.remove("cursor-pointer", "hover:bg-accent");
                }
            }
            datesEl.appendChild(div);
        });
    }
}
