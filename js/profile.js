// js/profile.js

// --- Control del Menú de Perfil ---
function toggleProfileMenu() {
    const menu = document.getElementById('profile-menu');
    const abrir = !menu.classList.contains('is-open');

    closeDropdownMenus(); // Cierra otros menús abiertos

    if (abrir) {
        menu.classList.add('is-open');
        document.getElementById('profile-button').setAttribute('aria-expanded', 'true');
    }
}

// Modificar la función global para incluir el menú de perfil al cerrar
function closeDropdownMenus() {
    const animalMenu = document.getElementById('animal-menu');
    const configMenu = document.getElementById('config-menu');
    const profileMenu = document.getElementById('profile-menu');

    if (animalMenu) animalMenu.classList.remove('is-open');
    if (configMenu) configMenu.classList.remove('is-open');
    if (profileMenu) profileMenu.classList.remove('is-open');

    if (document.getElementById('animal-button')) 
        document.getElementById('animal-button').setAttribute('aria-expanded', 'false');
    if (document.getElementById('config-button')) 
        document.getElementById('config-button').setAttribute('aria-expanded', 'false');
    if (document.getElementById('profile-button')) 
        document.getElementById('profile-button').setAttribute('aria-expanded', 'false');
}

// --- Inicialización al cargar la página ---
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Cargar el nombre real del usuario conectado
    // 1. Cargar el nombre real y la foto de perfil del usuario conectado
    fetch('php/auth/get_user.php')
        .then(response => {
            if (!response.ok) {
                window.location.href = 'login.html';
                throw new Error('Sesión no iniciada');
            }
            return response.json();
        })
        .then(data => {
            if (data.loggedIn) {
                const usernameEl = document.getElementById('profile-username');
                const menuUsernameEl = document.getElementById('profile-menu-username');
                const avatarContainer = document.getElementById('profile-avatar');
                
                if (usernameEl) usernameEl.textContent = data.username;
                if (menuUsernameEl) menuUsernameEl.textContent = data.username;

                // Si el usuario tiene una foto de perfil guardada, mostrarla en el círculo
                if (avatarContainer && data.profile_pic) {
                    avatarContainer.innerHTML = `<img src="${data.profile_pic}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                }
            } else {
                window.location.href = 'login.html';
            }
        })
        .catch(error => {
            console.error('Error al verificar la sesión del usuario:', error);
        });

    // 2. Opción "Mi perfil"
    const myProfileBtn = document.getElementById('my-profile-button');
    if (myProfileBtn) {
        myProfileBtn.addEventListener('click', function() {
            closeDropdownMenus();
            window.location.href = 'php/usuario/perfil.php';
        });
    }

    // 3. Opción "Configuración de cuenta"
    const accountSettingsBtn = document.getElementById('account-settings-button');
    if (accountSettingsBtn) {
        accountSettingsBtn.addEventListener('click', function() {
            closeDropdownMenus();
            window.location.href = 'php/usuario/configuracion.php';
        });
    }

    // 4. Opción "Cerrar sesión"
    const logoutBtn = document.getElementById('logout-button');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            closeDropdownMenus();
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                fetch('php/usuario/logout.php')
                    .then(() => {
                        window.location.href = 'login.html';
                    })
                    .catch(() => {
                        window.location.href = 'login.html';
                    });
            }
        });
    }
});

// --- Cerrar menús al hacer clic fuera o presionar Escape ---
document.addEventListener('click', function(event) {
    if (
        !event.target.closest('#animal-dropdown') &&
        !event.target.closest('#config-dropdown') &&
        !event.target.closest('#profile-container')
    ) {
        closeDropdownMenus();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDropdownMenus();
    }
});