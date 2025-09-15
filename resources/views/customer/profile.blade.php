@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('dashboard-type', 'Customer')
@section('page-title', 'My Profile')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.shop') }}">
            <i class="fas fa-shopping-bag me-2"></i> Shop
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.cart') }}">
            <i class="fas fa-shopping-cart me-2"></i> Cart
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('customer.orders') }}">
            <i class="fas fa-list me-2"></i> My Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('customer.profile') }}">
            <i class="fas fa-user me-2"></i> Profile
        </a>
    </li>
@endsection

@section('header-actions')
    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <form id="profileForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dateOfBirth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your full address">{{ $user->address ?? '' }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="zipCode" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zipCode" name="zip_code">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Change Password</h5>
            </div>
            <div class="card-body">
                <form id="passwordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password *</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="newPassword" class="form-label">New Password *</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Profile Picture -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Profile Picture</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <i class="fas fa-user fa-3x text-white"></i>
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <input type="file" class="form-control" id="profilePicture" accept="image/*">
                </div>
                <button class="btn btn-outline-primary btn-sm" onclick="uploadProfilePicture()">
                    <i class="fas fa-upload me-1"></i> Upload Picture
                </button>
            </div>
        </div>
        
        <!-- Account Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Account Statistics</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Member Since:</span>
                    <span>{{ $user->created_at->format('M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Total Orders:</span>
                    <span>0</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Total Spent:</span>
                    <span>$0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Account Status:</span>
                    <span class="badge bg-success">Active</span>
                </div>
            </div>
        </div>
        
        <!-- Preferences -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Preferences</h5>
            </div>
            <div class="card-body">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    <label class="form-check-label" for="emailNotifications">
                        Email Notifications
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="smsNotifications">
                    <label class="form-check-label" for="smsNotifications">
                        SMS Notifications
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="promotionalEmails" checked>
                    <label class="form-check-label" for="promotionalEmails">
                        Promotional Emails
                    </label>
                </div>
                <button class="btn btn-outline-primary btn-sm w-100" onclick="savePreferences()">
                    <i class="fas fa-save me-1"></i> Save Preferences
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    const profileData = Object.fromEntries(formData);
    
    // Show success message
    alert('Profile updated successfully!');
    console.log('Profile data:', profileData);
    
    // In a real app, you would send an AJAX request to update the profile
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        alert('New passwords do not match!');
        return;
    }
    
    if (newPassword.length < 8) {
        alert('New password must be at least 8 characters long!');
        return;
    }
    
    // Show success message
    alert('Password updated successfully!');
    this.reset();
    
    // In a real app, you would send an AJAX request to update the password
});

function uploadProfilePicture() {
    const fileInput = document.getElementById('profilePicture');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('Please select a file first!');
        return;
    }
    
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file!');
        return;
    }
    
    // Show success message
    alert('Profile picture uploaded successfully!');
    
    // In a real app, you would upload the file to the server
    console.log('Uploading file:', file.name);
}

function savePreferences() {
    const emailNotifications = document.getElementById('emailNotifications').checked;
    const smsNotifications = document.getElementById('smsNotifications').checked;
    const promotionalEmails = document.getElementById('promotionalEmails').checked;
    
    const preferences = {
        email_notifications: emailNotifications,
        sms_notifications: smsNotifications,
        promotional_emails: promotionalEmails
    };
    
    alert('Preferences saved successfully!');
    console.log('Preferences:', preferences);
    
    // In a real app, you would send an AJAX request to save preferences
}
</script>
@endsection
@endsection
