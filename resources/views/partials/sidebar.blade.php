<aside id="sidebar" class="bg-dark text-white p-3">
        <!-- Header do Sidebar (Logo/Título + Botão Toggle) -->
        <div class="sidebar-header d-flex align-items-center justify-content-between mb-4">
            <span class="fs-5 fw-bold sidebar-text text-truncate">Gestor de Frota</span>
            <button id="sidebarToggle" class="btn btn-outline-light border-0">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Links de Navegação -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" 
                    class="nav-link d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line fa-fw me-3 icon"> </i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-white d-flex align-items-center">
                    <i class="fa-solid fa-route fa-fw me-3 icon"></i>
                    <span class="sidebar-text">Nova Reserva</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('vehicles.index') }}" 
                    class="nav-link d-flex align-items-center {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-car-side fa-fw me-3 icon"></i>
                    <span class="sidebar-text">Veículos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('drivers.index') }}" 
                    class="nav-link d-flex align-items-center {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-id-card fa-fw me-3 icon"></i>
                    <span class="sidebar-text">Motoristas</span>
                </a>
            </li>
        </ul>
    </aside>