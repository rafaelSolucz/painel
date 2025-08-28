const body = document.querySelector("body"),
      sidebar = body.querySelector(".sidebar"),
      toggle = body.querySelector(".toggle"),
      modeSwitch = body.querySelector(".toggle-switch");

// Função para salvar o estado do menu no localStorage
const saveMenuState = (isClosed) => {
    localStorage.setItem("menuClosed", isClosed ? "true" : "false");
};

// Função para carregar o estado do menu armazenado
const loadMenuState = () => {
    const savedState = localStorage.getItem("menuClosed");
    if (savedState === "true") {
        sidebar.classList.add("close");
    } else {
        sidebar.classList.remove("close");
    }
};

// Carregar o estado do menu ao carregar a página
loadMenuState();

toggle.addEventListener("click", () => {
    const isClosed = sidebar.classList.toggle("close");
    saveMenuState(isClosed); // Salvar o estado do menu
});

// Função para salvar o estado do tema no localStorage
const saveThemeState = (isDarkMode) => {
    localStorage.setItem("theme", isDarkMode ? "dark" : "light");
};

// Função para carregar o tema armazenado
const loadThemeState = () => {
    const savedTheme = localStorage.getItem("theme");
    if (savedTheme === "dark") {
        body.classList.add("dark");
    } else {
        body.classList.remove("dark");
    }
};

// Carregar o estado do tema ao carregar a página
loadThemeState();

modeSwitch.addEventListener("click", () => {
    const isDarkMode = body.classList.toggle("dark");
    saveThemeState(isDarkMode); // Salvar o estado do tema
});

// Função para abrir e fechar submenu e salvar estado no localStorage
document.addEventListener("DOMContentLoaded", function () {
    const submenus = document.querySelectorAll(".submenu");
    
    submenus.forEach((submenu, index) => {
        const submenuItens = submenu.nextElementSibling; // Pega a UL seguinte ao submenu

        // Carregar estado do localStorage
        const estadoSalvo = localStorage.getItem(`submenuAberto-${index}`);
        if (estadoSalvo === "true") {
            submenuItens.classList.add("open");
        }

        // Evento de clique para abrir/fechar o submenu
        submenu.addEventListener("click", () => {
            submenuItens.classList.toggle("open");

            // Salvar o estado no localStorage
            const isOpen = submenuItens.classList.contains("open");
            localStorage.setItem(`submenuAberto-${index}`, isOpen);
        });
    });
});