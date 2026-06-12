const calendar = document.getElementById("calendar");

if (calendar) {
    const thisMonth = document.getElementById("this-month-calendar");
    const nextMonth = document.getElementById("next-month-calendar");
    const prevMonthBtn = document.getElementById("prev-month-btn");
    const nextMonthBtn = document.getElementById("next-month-btn");

    function show(element) {
        element.style.display = "block";
    }
    function hide(element) {
        element.style.display = "none";
    }

    prevMonthBtn.addEventListener("click", () => {
        show(thisMonth);
        hide(nextMonth);
    });
    nextMonthBtn.addEventListener("click", () => {
        show(nextMonth);
        hide(thisMonth);
    });
}
