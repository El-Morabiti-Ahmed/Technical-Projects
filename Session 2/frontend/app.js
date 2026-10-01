const API_BASE = "../backend/api.php";

let state = {
    platforms: [],
    games: []
};

// DOM References
const tbodyPlatforms = document.querySelector("#tbody-platforms");
const tbodyGames = document.querySelector("#tbody-games");
const selectPlatform = document.querySelector("#select-platform");

async function apiRequest(entity, method = "GET", payload = null) {
    const config = {
        method: method,
        headers: { "Content-Type": "application/json" }
    };

    if (payload) {
        config.body = JSON.stringify(payload);
    }

    const response = await fetch(`${API_BASE}?entity=${entity}`, config);
    return await response.json();
}

async function loadPlatforms() {
    state.platforms = await apiRequest("platforms");
    tbodyPlatforms.innerHTML = "";
    selectPlatform.innerHTML = '<option value="">Select Platform...</option>';

    state.platforms.forEach(p => {
        tbodyPlatforms.insertAdjacentHTML("beforeend", `
            <tr>
                <td class="p-2 font-mono text-xs text-slate-400">#${p.id}</td>
                <td class="p-2 font-semibold text-slate-200">${p.name}</td>
                <td class="p-2">${p.vendor}</td>
            </tr>
        `);

        selectPlatform.insertAdjacentHTML("beforeend", `<option value="${p.id}">${p.name}</option>`);
    });
}

async function loadGames() {
    state.games = await apiRequest("games");
    tbodyGames.innerHTML = "";

    state.games.forEach(game => {
        const platform = state.platforms.find(p => p.id == game.platform_id);

        tbodyGames.insertAdjacentHTML("beforeend", `
            <tr>
                <td class="p-2 font-semibold text-indigo-300">${game.title}</td>
                <td class="p-2 text-slate-300">${game.genre}</td>
                <td class="p-2 text-emerald-400">${platform ? platform.name : "Unknown"}</td>
            </tr>
        `);
    });
}

async function loadAllData() {
    await loadPlatforms();
    await loadGames();
}

document.addEventListener("DOMContentLoaded", () => {
    
    // 1. Add Platform
    document.querySelector("#form-platform").addEventListener("submit", async (e) => {
        e.preventDefault();
        const payload = {
            name: document.querySelector("#platform-name").value,
            vendor: document.querySelector("#platform-vendor").value
        };

        await apiRequest("platforms", "POST", payload);
        e.target.reset();
        await loadAllData();
    });

    // 2. Add Game (Main)
    document.querySelector("#form-game").addEventListener("submit", async (e) => {
        e.preventDefault();
        const payload = {
            title: document.querySelector("#game-title").value,
            genre: document.querySelector("#game-genre").value,
            platform_id: parseInt(selectPlatform.value)
        };

        await apiRequest("games", "POST", payload);
        e.target.reset();
        await loadGames();
    });

    loadAllData();
});