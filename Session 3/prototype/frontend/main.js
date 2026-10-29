const form = document.getElementById('form');
const showbtn = document.getElementById('btn-show-form');
const sectionForm = document.getElementById('section-form');
const nameINP = document.getElementById('game-name');
const platINP = document.getElementById('game-platform');
const cancel = document.getElementById('btn-cancel-form');
const tbody = document.getElementById('table-games-body');
const savebtn = document.getElementById('btn-save');
const toast = document.getElementById('toast');

const APIpath = "../backend/gameController.php";


// GAME CLASS
class Game {
    #id;
    #name;
    #platform;

    constructor(id, name, platform) {
        this.#id = id;
        this.#name = name;
        this.#platform = platform;
    }

    getId() {
        return this.#id;
    }

    getName() {
        return this.#name;
    }

    getPlatform() {
        return this.#platform;
    }
}


// GAME MANAGER
class GameManager {
    constructor(APIpath) {
        this.APIpath = APIpath;
    }

    async getAll() {
        const response = await fetch(this.APIpath);

        if (!response.ok) {
            throw new Error("Failed to retrieve games.");
        }

        const games = await response.json();

        return games.map(game => {
            return new Game(
                game.id,
                game.name,
                game.platform
            );
        });
    }

    async create(name, platform) {
        const response = await fetch(this.APIpath, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                name: name,
                platform: platform
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(
                result.error || "Failed to create game."
            );
        }

        return result;
    }
}


// GAME CONTROLLER
class GameController {
    constructor(manager) {
        this.manager = manager;
        this.isLoading = false;
        this.toastTimer = null;
        this.hideToastTimer = null;

        this.bindEvents();
    }

    // EVENTS
    bindEvents() {
        showbtn.addEventListener("click", () => {
            sectionForm.hidden = false;
            nameINP.focus();
        });

        cancel.addEventListener("click", () => {
            if (this.isLoading) return;

            sectionForm.hidden = true;
            form.reset();
        });

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            this.createGame();
        });

        this.loadGames();
    }

    // LOADING STATE
    setLoading(state) {
        this.isLoading = state;

        savebtn.disabled = state;
        showbtn.disabled = state;
        cancel.disabled = state;

        savebtn.textContent = state
            ? "Saving..."
            : "Save";
    }

    // GET GAMES
    async loadGames() {
        const games = await this.manager.getAll();
        this.renderGames(games);
    }

    // RENDER GAMES
    renderGames(games) {
        tbody.innerHTML = '';

        games.forEach(game => {
            const row = document.createElement('tr');
            row.className = "hover:bg-gray-50 transition-colors";

            const id = document.createElement('td');
            id.className = "p-4 text-sm";
            id.textContent = game.getId();

            const name = document.createElement('td');
            name.className = "p-4 text-sm font-medium";
            name.textContent = game.getName();

            const platform = document.createElement('td');
            platform.className = "p-4 text-sm";
            platform.textContent = game.getPlatform();

            const actions = document.createElement('td');
            actions.className = "p-4 text-right space-x-2";

            const edit = document.createElement('button');
            edit.type = "button";
            edit.textContent = "EDIT";
            edit.disabled = true;
            edit.title = "Edit functionality is not implemented yet";
            edit.className = "text-primary-600 hover:text-primary-900 disabled:opacity-50 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 rounded";

            const remove = document.createElement('button');
            remove.type = "button";
            remove.textContent = "DELETE";
            remove.disabled = true;
            remove.title = "Delete functionality is not implemented yet";
            remove.className = "text-red-600 hover:text-red-900 disabled:opacity-50 disabled:cursor-not-allowed focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 rounded";

            actions.append(edit, remove);
            row.append(id, name, platform, actions);

            tbody.appendChild(row);
        });
    }

    // CREATE GAME
    async createGame() {
        if (this.isLoading) return;

        this.setLoading(true);

        try {
            await this.manager.create(
                nameINP.value,
                platINP.value
            );

            form.reset();
            sectionForm.hidden = true;

            this.showToast(
                "Game created successfully!",
                "success"
            );

            try {
                await this.loadGames();
            } catch (error) {
                this.showToast(
                    "Game was saved, but the list could not be refreshed.",
                    "error"
                );
            }

        } catch (error) {
            this.showToast(error.message, "error");

        } finally {
            this.setLoading(false);
        }
    }

    // TOAST NOTIFICATIONS
    showToast(message, type) {
        clearTimeout(this.toastTimer);
        clearTimeout(this.hideToastTimer);

        toast.textContent = message;

        toast.classList.remove(
            'hidden',
            'opacity-0',
            'translate-y-2',
            'bg-green-600',
            'bg-red-600'
        );

        toast.classList.add(
            type === "success"
                ? 'bg-green-600'
                : 'bg-red-600'
        );

        this.toastTimer = setTimeout(() => {
            toast.classList.add(
                'opacity-0',
                'translate-y-2'
            );

            this.hideToastTimer = setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);

        }, 3000);
    }
}


// INITIALIZATION
const manager = new GameManager(APIpath);
const controller = new GameController(manager);
