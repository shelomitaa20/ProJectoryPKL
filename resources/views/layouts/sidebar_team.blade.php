<nav class="sidebar">
    <ul class="nav flex-column mt-3">
        <!-- Dashboard Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('team.dashboard') ? 'active' : '' }}" href="{{ route('team.dashboard') }}">
                <i class="fas fa-tachometer-alt me-3"></i>
                <span>&nbsp;Dashboard</span>
            </a>
        </li>

       <!-- Profile Link -->
       <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('team.profile.edit') ? 'active' : '' }}" href="{{ route('team.profile.edit') }}">
                <i class="fas fa-user-edit me-3"></i>
                <span>&nbsp;Profile</span>
            </a>
        </li>

        <!-- Projects Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('team.projects', 'team.projects.detail') ? 'active' : '' }}" href="{{ route('team.projects') }}">
                <i class="fas fa-project-diagram me-3"></i>
                <span>&nbsp;Projects</span>
            </a>
        </li>
    </ul>
</nav>
