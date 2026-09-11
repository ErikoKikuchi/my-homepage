document.addEventListener("DOMContentLoaded", () => {
    const picker = document.getElementById("date-picker");
    const dateInput = document.getElementById("date-input");
    const addBtn = document.getElementById("add-date-btn");
    const list = document.getElementById("selected-dates");
    const selectedDates = new Set();

    function renderTag(value) {
        const li = document.createElement("li");
        li.className =
            "flex items-center gap-1 bg-forest-dark/10 text-forest-dark px-2 py-1 rounded text-sm";
        li.dataset.date = value;
        li.innerHTML = `
            <span>${value}</span>
            <input type="hidden" name="dates[]" value="${value}">
            <button type="button" class="remove-date text-red-600 ml-1">×</button>
        `;
        list.appendChild(li);

        li.querySelector(".remove-date").addEventListener("click", () => {
            selectedDates.delete(value);
            li.remove();
        });
    }

    // エラーで戻ってきた際、以前選択していた日付を復元
    const oldDates = picker.dataset.oldDates;
    if (oldDates) {
        oldDates.split(",").forEach((value) => {
            selectedDates.add(value);
            renderTag(value);
        });
    }

    addBtn.addEventListener("click", () => {
        const value = dateInput.value;
        if (!value || selectedDates.has(value)) return;

        selectedDates.add(value);
        renderTag(value);
        dateInput.value = "";
    });
});
