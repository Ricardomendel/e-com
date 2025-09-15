<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <!-- Logo/Brand -->
                    <div class="p-4 text-center border-bottom border-light border-opacity-25">
                        <h4 class="text-white mb-1">{{ config('app.name') }}</h4>
                        <small class="text-white-50">@yield('dashboard-type') Panel</small>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="flex-fill">
                        <ul class="nav flex-column py-3">
                            @yield('sidebar-menu')
                        </ul>
                    </nav>
                    
                    <!-- User Info & Logout -->
                    <div class="p-3 border-top border-light border-opacity-25 mt-auto">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <div class="text-white fw-bold small">{{ auth()->user()->name }}</div>
                                <div class="text-white-50 small">{{ is_array(auth()->user()->role) ? implode(', ', auth()->user()->role) : auth()->user()->role }}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/" class="btn btn-outline-light btn-sm flex-fill">
                                <i class="fas fa-home me-1"></i> Home
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0">
                <div class="main-content min-vh-100">
                    <!-- Header -->
                    <header class="bg-white shadow-sm border-bottom">
                        <div class="container-fluid px-4 py-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <!-- Breadcrumb -->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            @yield('breadcrumb')
                                        </ol>
                                    </nav>
                                    <h1 class="h3 mb-0 text-gray-800">@yield('page-title')</h1>
                                </div>
                                <div class="col-auto">
                                    @yield('header-actions')
                                </div>
                            </div>
                        </div>
                    </header>
                    
                    <!-- Content -->
                    <main class="container-fluid px-4 py-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('error') || $errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @if(session('error'))
                                    {{ session('error') }}
                                @endif
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
