<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'LaraCommerce') }} - E-Commerce Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/><circle cx="900" cy="800" r="80" fill="url(%23a)"/></svg>') no-repeat center center;
            background-size: cover;
            pointer-events: none;
            z-index: 0;
        }
        
        .main-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .hero-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            letter-spacing: -1px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .action-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
            position: relative;
        }
        
        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }
        
        .card-header-custom {
            padding: 30px 30px 20px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white"/><circle cx="80" cy="30" r="1.5" fill="white"/><circle cx="40" cy="70" r="1" fill="white"/><circle cx="90" cy="80" r="2.5" fill="white"/><circle cx="10" cy="90" r="1" fill="white"/></svg>');
        }
        
        .login-header { background: var(--primary-gradient); }
        .customer-header { background: var(--success-gradient); }
        .merchant-header { background: var(--warning-gradient); }
        .staff-header { background: var(--secondary-gradient); }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .card-description {
            font-size: 0.95rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .card-body-custom {
            padding: 30px;
        }
        
        .btn-custom {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-custom:hover::before {
            left: 100%;
        }
        
        .btn-login { background: var(--primary-gradient); color: white; }
        .btn-customer { background: var(--success-gradient); color: white; }
        .btn-merchant { background: var(--warning-gradient); color: white; }
        .btn-staff { background: var(--secondary-gradient); color: white; }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 20px 0 0 0;
        }
        
        .features-list li {
            padding: 8px 0;
            color: #666;
            display: flex;
            align-items: center;
        }
        
        .features-list i {
            color: #28a745;
            margin-right: 10px;
            width: 16px;
        }
        
        .alert-custom {
            background: rgba(255,255,255,0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        
        .floating-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .action-cards {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .card-header-custom,
            .card-body-custom {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="main-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="hero-section">
                <h1 class="hero-title">{{ config('app.name', 'LaraCommerce') }}</h1>
                <p class="hero-subtitle">
                    Your complete e-commerce solution. Connect customers, merchants, and staff in one powerful platform.
                </p>
            </div>
            
            <!-- Action Cards -->
            <div class="action-cards">
                <!-- Login Card -->
                <div class="action-card">
                    <div class="card-header-custom login-header">
                        <div class="card-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3 class="card-title">Already Have Account?</h3>
                        <p class="card-description">Sign in to access your dashboard</p>
                    </div>
                    <div class="card-body-custom">
                        <form method="POST" action="{{ url('/portal/login') }}" id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-custom btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Secure login system</li>
                            <li><i class="fas fa-check"></i> Role-based access control</li>
                            <li><i class="fas fa-check"></i> Remember login option</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Customer Registration -->
                <div class="action-card">
                    <div class="card-header-custom customer-header">
                        <div class="card-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="card-title">Shop With Us</h3>
                        <p class="card-description">Join as a customer and start shopping</p>
                    </div>
                    <div class="card-body-custom">
                        <p class="text-muted mb-3">
                            <i class="fas fa-star text-warning me-1"></i>
                            Create your customer account and enjoy:
                        </p>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Browse thousands of products</li>
                            <li><i class="fas fa-check"></i> Secure checkout process</li>
                            <li><i class="fas fa-check"></i> Order tracking & history</li>
                            <li><i class="fas fa-check"></i> Instant account activation</li>
                        </ul>
                        <a href="{{ url('/register/customer') }}" class="btn btn-custom btn-customer">
                            <i class="fas fa-user-plus me-2"></i>Register as Customer
                        </a>
                    </div>
                </div>
                
                <!-- Merchant Registration -->
                <div class="action-card">
                    <div class="card-header-custom merchant-header">
                        <div class="card-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="card-title">Sell Your Products</h3>
                        <p class="card-description">Join as a merchant and grow your business</p>
                    </div>
                    <div class="card-body-custom">
                        <p class="text-muted mb-3">
                            <i class="fas fa-chart-line text-success me-1"></i>
                            Start selling online today:
                        </p>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> List unlimited products</li>
                            <li><i class="fas fa-check"></i> Manage orders & inventory</li>
                            <li><i class="fas fa-check"></i> Track sales & finances</li>
                            <li><i class="fas fa-check"></i> Professional dashboard</li>
                        </ul>
                        <a href="{{ url('/register/merchant') }}" class="btn btn-custom btn-merchant">
                            <i class="fas fa-store me-2"></i>Register as Merchant
                        </a>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Requires approval by admin or staff
                        </small>
                    </div>
                </div>
                
                <!-- Staff Registration -->
                <div class="action-card">
                    <div class="card-header-custom staff-header">
                        <div class="card-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3 class="card-title">Join Our Team</h3>
                        <p class="card-description">Apply to become a staff member</p>
                    </div>
                    <div class="card-body-custom">
                        <p class="text-muted mb-3">
                            <i class="fas fa-shield-alt text-primary me-1"></i>
                            Staff members can:
                        </p>
                        <ul class="features-list">
                            <li><i class="fas fa-check"></i> Manage merchant approvals</li>
                            <li><i class="fas fa-check"></i> Monitor platform activity</li>
                            <li><i class="fas fa-check"></i> Handle customer support</li>
                            <li><i class="fas fa-check"></i> Access admin tools</li>
                        </ul>
                        <a href="{{ url('/register/staff') }}" class="btn btn-custom btn-staff">
                            <i class="fas fa-user-tie me-2"></i>Apply as Staff
                        </a>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Requires admin approval
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger alert-custom">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:
                    </h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Success Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-custom">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if (session('status'))
                <div class="alert alert-info alert-custom">
                    <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on scroll
            const cards = document.querySelectorAll('.action-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'slideInUp 0.6s ease forwards';
                    }
                });
            });
            
            cards.forEach((card) => {
                observer.observe(card);
            });
            
            // Add CSS animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInUp {
                    from {
                        opacity: 0;
                        transform: translateY(50px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .action-card {
                    opacity: 0;
                }
            `;
            document.head.appendChild(style);
            
            // Form validation
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const email = this.querySelector('input[name="email"]').value;
                    const password = this.querySelector('input[name="password"]').value;
                    
                    if (!email || !password) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
                });
            }
        });
    </script>
</body>
</html>


