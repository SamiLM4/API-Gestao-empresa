// ui.js
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icon = document.createElement('span');
    icon.className = 'toast-icon';
    icon.innerHTML = type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️';
    
    const text = document.createElement('span');
    text.className = 'toast-text';
    text.textContent = message;
    
    toast.appendChild(icon);
    toast.appendChild(text);
    
    toastContainer.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

function openModal(modalId) {
    document.getElementById(modalId).classList.add('show');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('show');
}

function logout() {
    localStorage.removeItem('token');
    window.location.href = 'http://localhost:8080/index.HTML';
}

function getCommonNavbar() {
    return `
    <nav class="navbar">
        <div class="logo">SamiAPI</div>
        <ul>
            <li><a href="http://localhost:8080/html/paginaMain.html">Dashboard</a></li>
            <li><a href="http://localhost:8080/html/funcionarios.html">Funcionários</a></li>
            <li><a href="http://localhost:8080/html/departamento.html">Departamentos</a></li>
            <li><a href="http://localhost:8080/html/historicoCargos.html">Cargos</a></li>
            <li><a href="http://localhost:8080/html/perfil.html">Perfis</a></li>
            <li><a href="http://localhost:8080/html/dados.html">Relatórios</a></li>
            <li><a href="#" class="logout-btn" onclick="logout(); return false;">Sair</a></li>
        </ul>
    </nav>
    `;
}

document.addEventListener('DOMContentLoaded', () => {
    // Inject navbar if element exists
    const navPlaceholder = document.getElementById('navbar-placeholder');
    if (navPlaceholder) {
        navPlaceholder.innerHTML = getCommonNavbar();
        
        // set active link
        const currentPath = window.location.pathname;
        const links = navPlaceholder.querySelectorAll('a');
        links.forEach(link => {
            if (link.getAttribute('href').includes(currentPath)) {
                link.classList.add('active');
            }
        });
    }

    // Check auth on protected pages
    if (!window.location.pathname.toLowerCase().includes('index.html')) {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = 'http://localhost:8080/index.HTML';
        }
    }
});
