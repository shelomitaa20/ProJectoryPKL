<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- MDB CSS (if you're using MDBootstrap) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet">
    <style>
        .gradient-custom-2 {
            /* fallback for old browsers */
            background: #6a11cb;
            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, #6a11cb, #2575fc);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        @media (min-width: 768px) {
            .gradient-form {
                height: 100vh;
            }
        }

        @media (min-width: 769px) {
            .gradient-custom-2 {
                border-top-right-radius: .3rem;
                border-bottom-right-radius: .3rem;
            }
        }
    </style>
</head>
<body>

<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">

                <div class="text-center">
                  <img src="{{ asset('images/logo.png') }}" style="width: 185px;" alt="logo">
                  <h4 class="mt-1 mb-5 pb-1">ProJectory</h4>
                </div>

                <form method="POST" action="{{ route('register') }}">
                  @csrf
                  <h4 class="mb-3">Create Your Account</h4>

                  <div class="form-outline mb-4">
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                      placeholder="Full Name" value="{{ old('name') }}" required autofocus />
                    <label class="form-label" for="name">Full Name</label>
                    @error('name')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-outline mb-4">
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                      placeholder="Email address" value="{{ old('email') }}" required />
                    <label class="form-label" for="email">Email</label>
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required />
                    <label class="form-label" for="password">Password</label>
                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required />
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                  </div>

                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" type="submit">Register</button>
                  </div>

                  <div class="d-flex align-items-center justify-content-center pb-4">
                    <p class="mb-0 me-2">Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                  </div>

                </form>

              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                <h4 class="mb-4">Welcome to ProJectory</h4>
                <p class="small mb-0">ProJectory is your ultimate tool for efficient and effective project management. Our platform is designed to help organize, track, and complete projects with ease.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- MDB JS (if you're using MDBootstrap) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
</body>
</html>
