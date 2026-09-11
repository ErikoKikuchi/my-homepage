const nameInput = document.getElementById("name");
const phoneInput = document.getElementById("phone");
const userIdInput = document.getElementById("user_id");
const resultsList = document.getElementById("client-search-results");

let debounceTimer;

nameInput.addEventListener("input", () => {
    userIdInput.value = "";
    clearTimeout(debounceTimer);

    const q = nameInput.value.trim();
    if (!q) {
        resultsList.classList.add("hidden");
        resultsList.innerHTML = "";
        return;
    }

    debounceTimer = setTimeout(() => searchClients(q), 300);
});

phoneInput.addEventListener("input", () => {
    userIdInput.value = "";
});

document.addEventListener("click", (event) => {
    if (
        !nameInput.contains(event.target) &&
        !resultsList.contains(event.target)
    ) {
        resultsList.classList.add("hidden");
        resultsList.innerHTML = "";
    }
});

async function searchClients(q) {
    const response = await fetch(
        `/pilates/admin/client/search?q=${encodeURIComponent(q)}`,
        {
            headers: { Accept: "application/json" },
        },
    );
    const clients = await response.json();
    renderResults(clients);
}

function renderResults(clients) {
    resultsList.innerHTML = "";

    if (clients.length === 0) {
        resultsList.classList.add("hidden");
        return;
    }

    clients.forEach((client) => {
        const li = document.createElement("li");
        li.className = "px-3 py-2 cursor-pointer hover:bg-forest/10";
        li.textContent = `${client.name}${client.phone ? "(" + client.phone + ")" : ""}${client.relationship_note ? " - " + client.relationship_note : ""}`;
        li.addEventListener("click", () => selectClient(client));
        resultsList.appendChild(li);
    });

    resultsList.classList.remove("hidden");
}

function selectClient(client) {
    nameInput.value = client.name;
    phoneInput.value = client.phone ?? "";
    userIdInput.value = client.id;
    document.getElementById("relationship_note").value = "";
    resultsList.classList.add("hidden");
    resultsList.innerHTML = "";
}
