const getStoredTheme = () => localStorage.getItem("theme");

const setStoredTheme = (theme) => {
    localStorage.setItem("theme", theme);
};

const getPreferredTheme = () => {
    const storedTheme = getStoredTheme();

    if (storedTheme) {
        return storedTheme;
    }

    return window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light";
};

const setTheme = (theme) => {
    if (theme === "auto") {
        document.documentElement.setAttribute(
            "data-bs-theme",
            window.matchMedia("(prefers-color-scheme: dark)").matches
                ? "dark"
                : "light",
        );
    } else {
        document.documentElement.setAttribute("data-bs-theme", theme);
    }
};

const icons = {
    light: "fa-sun",
    dark: "fa-moon",
    auto: "fa-circle-half-stroke",
};

const labels = {
    light: "Claro",
    dark: "Escuro",
    auto: "Automático",
};

setTheme(getPreferredTheme());
updateThemeButton(getPreferredTheme());

document.querySelectorAll("[data-bs-theme-value]").forEach((button) => {
    button.addEventListener("click", () => {
        const theme = button.dataset.bsThemeValue;
        setStoredTheme(theme);
        setTheme(theme);
        updateThemeButton(theme);
    });
});

window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", () => {
        if (localStorage.getItem("theme") === "auto") {
            setTheme("auto");
        }
    });

function updateThemeButton(theme) {
    const icon = document.getElementById("theme-icon");
    const text = document.getElementById("theme-text");

    if (!icon || !text) return;

    // Atualiza o ícone
    icon.className = `fa-solid ${icons[theme]}`;

    // Atualiza o texto
    text.textContent = labels[theme];

    // Destaca a opção selecionada
    document.querySelectorAll("[data-bs-theme-value]").forEach((button) => {
        button.classList.remove("active");

        if (button.dataset.bsThemeValue === theme) {
            button.classList.add("active");
        }
    });
}
