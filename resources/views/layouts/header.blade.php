<header class="header">
    <div class="brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span>ProJectory</span>
    </div>
    <div class="user-info dropdown mx-3">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle btn btn-primary" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://via.placeholder.com/40' }}" 
                alt="User" 
                width="30" 
                height="30" 
                class="rounded-circle me-2" 
                style="object-fit: cover;">
            <strong class="user-info-name mx-1">{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small shadow mt-2" aria-labelledby="userDropdown">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</header>
