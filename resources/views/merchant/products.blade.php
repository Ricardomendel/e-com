@extends('layouts.dashboard')

@section('title', 'My Products')
@section('dashboard-type', 'Merchant')
@section('page-title', 'My Products')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('merchant.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Products</li>
@endsection

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('merchant.products') }}">
            <i class="fas fa-box me-2"></i> Products
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.orders') }}">
            <i class="fas fa-shopping-cart me-2"></i> Orders
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('merchant.finances') }}">
            <i class="fas fa-chart-line me-2"></i> Finances
        </a>
    </li>
@endsection

@section('header-actions')
    <a href="{{ route('merchant.dashboard') }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
    </a>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fas fa-plus me-1"></i> Add Product
    </button>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">My Products</h3>
                    <button class="btn btn-primary" onclick="addProduct()">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>GH₵{{ number_format($product->price / 100, 2) }}</td>
                                    <td>{{ $product->stock ?? 0 }}</td>
                                    <td>
                                        <span class="badge badge-{{ $product->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($product->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewProduct({{ $product->id }})">
                                            View
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editProduct({{ $product->id }})">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteProduct({{ $product->id }})" onclick="return confirm('Delete this product?')">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No products found. <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">Add your first product</a></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="productName" class="form-label">Product Name *</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-select" id="productCategory" name="category">
                                <option value="">Select Category</option>
                                <option value="electronics">Electronics</option>
                                <option value="clothing">Clothing</option>
                                <option value="books">Books</option>
                                <option value="home">Home & Garden</option>
                                <option value="sports">Sports</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                                <label for="productPrice" class="form-label">Price (GH₵) *</label>
                                <input type="number" class="form-control" id="productPrice" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productStock" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="productStock" name="stock" min="0" value="0">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" rows="3" placeholder="Describe your product..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="productImage" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="image" accept="image/*">
                        <small class="form-text text-muted">Upload an image for your product (optional)</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="productActive" name="active" checked>
                            <label class="form-check-label" for="productActive">
                                Product is active and available for sale
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('addProductForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating...';
    submitButton.disabled = true;
    
    // Get form data
    const formData = new FormData(this);
    
    // Send AJAX request to create product
    fetch('/products', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            // Don't set Content-Type header - let browser set it for FormData
        },
        credentials: 'same-origin',
        body: formData
    })
    .then(async response => {
        if (!response.ok) {
            // Try to parse JSON, fallback to text
            let payload;
            try { payload = await response.json(); } catch (_) { payload = { message: await response.text() }; }
            const msg = (payload && (payload.error || payload.message)) ? (payload.error || payload.message) : 'Server Error';
            throw new Error(`HTTP ${response.status}: ${msg}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            alert('Product "' + data.product.name + '" created successfully!');
            
            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
            this.reset();
            
            // Reload page to show new product
            window.location.reload();
        } else {
            // Handle validation errors
            if (data.errors) {
                let errorMessage = 'Validation errors:\n';
                Object.keys(data.errors).forEach(field => {
                    errorMessage += `• ${field}: ${data.errors[field].join(', ')}\n`;
                });
                alert(errorMessage);
            } else {
                alert('Error: ' + (data.error || data.message || 'Failed to create product'));
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error occurred. Please check:\n1. You are logged in as a merchant\n2. Your internet connection\n3. The server is running\n\nError details: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

function editProduct(id) {
    alert('Edit product feature coming soon! Product ID: ' + id);
}

function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        fetch(`/products/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product deleted successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Failed to delete product'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the product.');
        });
    }
}

function viewProduct(id) {
    alert('View product feature coming soon! Product ID: ' + id);
}
</script>
@endsection
@endsection
