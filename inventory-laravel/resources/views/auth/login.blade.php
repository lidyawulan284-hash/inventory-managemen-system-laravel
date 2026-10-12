<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-card m-3">
    <div class="login-header">
        <div class="d-inline-flex p-3 bg-white bg-opacity-10 rounded-circle mb-3">
            <i class="bi bi-box-seam-fill fs-1 text-white"></i>
        </div>
        <h4 class="fw-bold mb-1">Inventory Management</h4>
        <p class="mb-0 text-white-50" style="font-size: 0.88rem;">Sistem Pengelolaan Stok & Relasi Database</p>
    </div>

    <div class="p-4 p-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-center py-2" role="alert" style="font-size: 0.88rem;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show text-center py-2" role="alert" style="font-size: 0.88rem;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="login" class="form-label fw-semibold text-secondary" style="font-size: 0.88rem;">Email atau Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person-fill"></i></span>
                    <input type="text"
                           class="form-control @error('login') is-invalid @enderror"
                           id="login"
                           name="login"
                           value="{{ old('login') }}"
                           placeholder="admin@inventory.com / admin"
                           required
                           autofocus>
                </div>
                @error('login')
                    <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary" style="font-size: 0.88rem;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock-fill"></i></span>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required>
                </div>
                @error('password')
                    <div class="text-danger mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-secondary" for="remember" style="font-size: 0.85rem;">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
            </button>
        </form>

        <!-- Akun Demo Box -->
        <div class="mt-4 p-3 bg-light rounded-3 border" style="font-size: 0.82rem;">
            <div class="fw-bold text-dark mb-1"><i class="bi bi-key-fill text-warning me-1"></i> Akun Demo Seeder:</div>
            <div class="d-flex justify-content-between text-muted mb-1">
                <span><strong>Admin:</strong> <code>admin</code> atau <code>admin@inventory.com</code></span>
                <span>Pass: <code>password123</code></span>
            </div>
            <div class="d-flex justify-content-between text-muted">
                <span><strong>Staff:</strong> <code>staff</code> atau <code>staff@inventory.com</code></span>
                <span>Pass: <code>password123</code></span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
