<aside id="sidebar" class="bg-dark text-white p-3 d-flex flex-column">
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
        @can('gerenciar usuarios')
            <li class="nav-item">
                <a href="{{ route('users.index') }}"
                    class="nav-link d-flex align-items-center {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user fa-fw me-3 icon"> </i>
                    <span class="sidebar-text">Usuários</span>
                </a>
            </li>
        @endcan
        <li class="nav-item">
            <a href="{{ route('trips.index') }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('trips.*') ? 'active' : '' }}">
                <i class="fa-solid fa-route fa-fw me-3 icon"></i>
                <span class="sidebar-text">Viagens</span>
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

    <!-- Área Inferior / Perfil do Usuário + Logout -->
    <div class="sidebar-footer mt-auto pt-3 border-top border-secondary">

        <!-- Info do Usuário Logado -->
        <div class="d-flex align-items-center mb-3 px-1 user-info">
            <!-- Ícone do Avatar -->
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                style="width: 38px; height: 38px;"  title="{{ auth()->user()->name }}">
                <i class="fa-solid fa-user fs-6"></i>
            </div>

            <!-- Textos (Escondidos ao recolher) -->
            <div class="sidebar-text text-truncate">
                <div class="fw-bold text-white fs-7 text-truncate">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-white-50 fs-8 text-truncate">
                    {{ auth()->user()->email }}
                </div>
            </div>
        </div>

        <!-- Botão de Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-white w-100 d-flex align-items-center btn-logout">
                <i class="fa-solid fa-right-from-bracket fa-fw me-3 icon text-danger"></i>
                <span class="sidebar-text text-danger fw-semibold">Sair</span>
            </button>
        </form>

    </div>
</aside>
