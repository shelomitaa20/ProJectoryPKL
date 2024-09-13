<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Include Header -->
    @include('layouts.header')

    <!-- Include Sidebar -->
    @include('layouts.sidebar_team')
    <!-- Main content -->
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('team.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mt-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mt-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">Overview Profile</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile" type="button" role="tab" aria-controls="edit-profile" aria-selected="false">Edit Profile</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">Change Password</button>
            </li>
        </ul>

        <div class="tab-content mt-3" id="myTabContent">
            <!-- Overview Profile Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                <div class="card-custom p-4">
                    <div class="d-flex align-items-center">
                        <!-- Foto Profil di sebelah kiri -->
                        <div class="profile-photo me-4">
                            <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://via.placeholder.com/150' }}" 
                                alt="Profile Photo" 
                                class="rounded-circle" 
                                width="150" 
                                height="150"
                                style="object-fit: cover;">
                        </div>
                        <!-- Detail Profil di sebelah kanan -->
                        <div class="profile-details mx-2">
                            <h5 class="card-title-profile-details text-primary">Profile Details</h5>
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th scope="row" class="profile-th">Full Name</th>
                                        <td class="profile-td">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="profile-th">Email</th>
                                        <td class="profile-td">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="profile-th">Role</th>
                                        <td class="profile-td">{{ $user->role }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Tab -->
            <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                <div class="card-custom p-4">
                    <form action="{{ route('team.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ Auth::user()->name }}" required>
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ Auth::user()->email }}" required>
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo">
                            @error('profile_photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-blue">Save Changes</button>
                    </form>
                </div>
            </div>

            <!-- Change Password Tab -->
            <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                <div class="card-custom p-4">
                    <form action="{{ route('team.profile.update.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                            @error('new_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Re-enter New Password</label>
                            <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required>
                            @error('new_password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-blue">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-dismiss alert after a few seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let alertInstance = new bootstrap.Alert(alert);
                    alertInstance.close();
                });
            }, 4000); // Alert will dismiss after 3 seconds
        });
    </script>

    <!-- Save and Restore Active Tab -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myTab = new bootstrap.Tab(document.querySelector('#myTab .nav-link.active'));

            // Save active tab in localStorage
            const tabs = document.querySelectorAll('#myTab .nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (e) {
                    localStorage.setItem('activeTab', e.target.id);
                });
            });

            // Load active tab from localStorage
            const activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                const selectedTab = document.querySelector('#' + activeTab);
                if (selectedTab) {
                    new bootstrap.Tab(selectedTab).show();
                }
            }
        });
    </script>
</body>

</html>