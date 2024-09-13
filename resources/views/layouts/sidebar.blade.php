<nav class="sidebar" id="sidebar">
    <ul class="nav flex-column mt-3">
        <!-- Dashboard Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-3"></i>
                <span>&nbsp;Dashboard</span>
            </a>
        </li>

        <!-- Profile Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
                <i class="fas fa-user-edit me-3"></i>
                <span>&nbsp;Profile</span>
            </a>
        </li>

        <!-- User Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="fas fa-users me-3"></i>
                <span>&nbsp;Users</span>
            </a>
        </li>

        <!-- Projects Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.projects', 'admin.projects.detail') ? 'active' : '' }}" href="{{ route('admin.projects') }}">
                <i class="fas fa-project-diagram me-3"></i>
                <span>&nbsp;Projects</span>
            </a>
        </li>

        <!-- Reports Link -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                <i class="fas fa-file-alt me-3"></i>
                <span>&nbsp;&nbsp;Reports</span>
            </a>
        </li>
    </ul>
</nav>
