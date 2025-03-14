@extends('layouts.seller')
@section('title', 'Edit Produk')

<style>
    /* Modern form styling */
    .page-container {
        min-height: 100vh;
        background-color: #f9fafb;
    }
    
    .form-card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .form-card:hover {
        transform: translateY(-2px);
    }
    
    .form-header {
        position: relative;
        padding: 2rem 2rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .form-subtitle {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    .form-body {
        padding: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .form-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 0.5rem;
        color: #4f46e5;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group:last-child {
        margin-bottom: 0;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .form-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    
    .form-input::placeholder {
        color: #9ca3af;
    }
    
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25rem;
        padding-right: 2.5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    .form-textarea {
        min-height: 8rem;
        resize: vertical;
    }
    
    .form-hint {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .form-error {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
    }
    
    /* Button styling */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .btn-primary {
        background-color: #4f46e5;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2), 0 4px 6px -2px rgba(79, 70, 229, 0.1);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-secondary {
        background-color: white;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .btn-secondary:hover {
        background-color: #f9fafb;
        color: #111827;
    }
    
    .btn-icon {
        margin-right: 0.5rem;
    }
    
    /* Image upload styling */
    .upload-container {
        margin-top: 1rem;
    }
    
    .upload-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        background-color: #f3f4f6;
        color: #4b5563;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }
    
    .upload-button:hover {
        background-color: #e5e7eb;
        color: #111827;
    }
    
    .upload-button i {
        margin-right: 0.5rem;
    }
    
    /* Image preview */
    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .preview-item {
        position: relative;
        width: 6rem;
        height: 6rem;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease;
    }
    
    .preview-item:hover {
        transform: scale(1.05);
    }
    
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .remove-btn {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        width: 1.5rem;
        height: 1.5rem;
        background-color: rgba(239, 68, 68, 0.9);
        color: white;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        line-height: 1;
        transition: all 0.2s ease;
    }
    
    .remove-btn:hover {
        background-color: #ef4444;
        transform: scale(1.1);
    }
    
    /* Toggle switch */
    .toggle-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 3.5rem;
        height: 2rem;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 2rem;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 1.5rem;
        width: 1.5rem;
        left: 0.25rem;
        bottom: 0.25rem;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
    }
    
    input:checked + .toggle-slider {
        background-color: #4f46e5;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(1.5rem);
    }
    
    .toggle-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #6b7280;
        transition: color 0.2s ease;
    }
    
    input:checked ~ .toggle-label {
        color: #4f46e5;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    /* Form progress indicator */
    .form-progress {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .form-progress::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        transform: translateY(-50%);
        z-index: 0;
    }
    
    .progress-step {
        position: relative;
        z-index: 1;
        background-color: white;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6b7280;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .progress-step.active {
        color: white;
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .progress-step.completed {
        color: white;
        background-color: #10b981;
        border-color: #10b981;
    }
    
    .progress-label {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
        white-space: nowrap;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-header {
            padding: 1.5rem 1.5rem 1rem;
        }
        
        .form-body {
            padding: 1.5rem;
        }
        
        .form-progress {
            margin-bottom: 1.5rem;
        }
        
        .progress-step {
            width: 2rem;
            height: 2rem;
            font-size: 0.875rem;
        }
        
        .progress-label {
            font-size: 0.7rem;
        }
    }
    
    /* Input focus animation */
    .input-focus-effect {
        position: relative;
    }
    
    .input-focus-effect::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: #4f46e5;
        transition: width 0.3s ease, left 0.3s ease;
    }
    
    .input-focus-effect:focus-within::after {
        width: 100%;
        left: 0;
    }
</style>

@section('content')
<div class="page-container py-8 px-4">
    <div class="container mx-auto max-w-5xl">
        <div class="mb-6">
            <a href="{{ route('seller.products.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors">
                <i class="iconify mr-1" data-icon="tabler:arrow-left"></i>
                Kembali ke Daftar Produk
            </a>
        </div>
        
        <div class="form-card animate-fade-in">
            <div class="form-header">
                <h1 class="form-title">Edit Produk</h1>
                <p class="form-subtitle">Perbarui informasi produk Anda untuk ditampilkan di toko</p>
            </div>
            
            <div class="form-body">
                <!-- Form Progress Indicator -->
                <div class="form-progress">
                    <div class="progress-step active" id="step1">
                        1
                        <span class="progress-label">Informasi Dasar</span>
                    </div>
                    <div class="progress-step" id="step2">
                        2
                        <span class="progress-label">Detail Produk</span>
                    </div>
                    <div class="progress-step" id="step3">
                        3
                        <span class="progress-label">Foto Produk</span>
                    </div>
                </div>
                
                <form id="productForm" action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Step 1: Basic Information -->
                    <div class="form-section" id="section1">
                        <h2 class="section-title">
                            <i class="iconify" data-icon="tabler:info-circle"></i>
                            Informasi Dasar
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group input-focus-effect">
                                <label for="name" class="form-label">Nama Produk <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="Masukkan nama produk" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-hint">Gunakan nama yang jelas dan mudah dicari</p>
                            </div>
                            
                            <div class="form-group input-focus-effect">
                                <label for="category_id" class="form-label">Kategori <span class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id" class="form-input form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group mt-6 toggle-container">
                            <label class="form-label">Status Produk</label>
                            <div class="flex items-center">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label ml-2" id="status-label">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="button" class="btn btn-primary w-full md:w-auto" onclick="goToStep(2)">
                                Lanjutkan
                                <i class="iconify ml-1" data-icon="tabler:arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Product Details -->
                    <div class="form-section hidden" id="section2">
                        <h2 class="section-title">
                            <i class="iconify" data-icon="tabler:list-details"></i>
                            Detail Produk
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group input-focus-effect">
                                <label for="price" class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" id="price" name="price" min="1000" class="form-input" placeholder="Contoh: 150000" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-hint">Minimal Rp 1.000</p>
                            </div>
                            
                            <div class="form-group input-focus-effect">
                                <label for="stock" class="form-label">Stok <span class="text-red-500">*</span></label>
                                <input type="number" id="stock" name="stock" min="0" class="form-input" placeholder="Jumlah stok produk" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="form-group input-focus-effect">
                                <label for="weight" class="form-label">Berat (gram) <span class="text-red-500">*</span></label>
                                <input type="number" id="weight" name="weight" min="100" class="form-input" placeholder="Contoh: 500" value="{{ old('weight', $product->weight) }}" required>
                                @error('weight')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-hint">Minimal 100 gram</p>
                            </div>
                            
                            <div class="form-group input-focus-effect">
                                <label for="size" class="form-label">Ukuran</label>
                                <input type="text" id="size" name="size" class="form-input" placeholder="Contoh: 10x15x5 cm" value="{{ old('size', $product->size) }}">
                                @error('size')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group mt-6 input-focus-effect">
                            <label for="description" class="form-label">Deskripsi Produk <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="5" class="form-input form-textarea" placeholder="Jelaskan detail produk Anda secara lengkap" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="form-hint">Minimal 20 karakter. Semakin detail deskripsi, semakin menarik bagi pembeli.</p>
                        </div>
                        
                        <div class="mt-6 flex flex-wrap gap-3">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                                <i class="iconify mr-1" data-icon="tabler:arrow-left"></i>
                                Kembali
                            </button>
                            <button type="button" class="btn btn-primary" onclick="goToStep(3)">
                                Lanjutkan
                                <i class="iconify ml-1" data-icon="tabler:arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 3: Product Images -->
                    <div class="form-section hidden" id="section3">
                        <h2 class="section-title">
                            <i class="iconify" data-icon="tabler:photo"></i>
                            Foto Produk
                        </h2>
                        
                        <div class="form-group">
                            <label class="form-label">Foto Produk Saat Ini</label>
                            <div class="image-preview" id="currentImages">
                                @if($product->images->isNotEmpty())
                                    @foreach($product->images as $image)
                                        <div class="preview-item" data-image-id="{{ $image->id }}">
                                            <img src="{{ Storage::url($image->path) }}" alt="{{ $product->name }}">
                                            <div class="remove-btn" onclick="removeCurrentImage({{ $image->id }}, this.parentNode)">×</div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500">Tidak ada foto produk</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group mt-6">
                            <label class="form-label">Tambah Foto Baru</label>
                            <p class="form-hint mb-3">Foto yang bagus dapat meningkatkan daya tarik produk Anda</p>
                            
                            <div class="upload-container">
                                <label for="productImages" class="upload-button">
                                    <i class="iconify" data-icon="tabler:upload"></i>
                                    Pilih Foto Produk
                                </label>
                                <input type="file" id="productImages" name="product-images[]" multiple accept="image/*" class="hidden">
                                <p class="form-hint mt-2">Format: JPG, PNG, GIF (Maks. 5MB)</p>
                            </div>
                            
                            <div id="imagePreview" class="image-preview"></div>
                            <div id="imageInputs" class="hidden"></div>
                            
                            @error('images')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-8 flex flex-wrap gap-3">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(2)">
                                <i class="iconify mr-1" data-icon="tabler:arrow-left"></i>
                                Kembali
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="iconify btn-icon" data-icon="tabler:device-floppy"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables
        const fileInput = document.getElementById('productImages');
        const imagePreview = document.getElementById('imagePreview');
        const imageInputs = document.getElementById('imageInputs');
        const form = document.getElementById('productForm');
        const statusToggle = document.getElementById('is_active');
        const statusLabel = document.getElementById('status-label');
        let tempImages = [];
        
        // Initialize status toggle
        if (statusToggle && statusLabel) {
            statusToggle.addEventListener('change', function() {
                statusLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
            });
        }
        
        // Initialize file input
        if (fileInput) {
            fileInput.addEventListener('change', handleFileSelect);
        }
        
        // Initialize form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm()) {
                    return;
                }
                
                // Show loading state
                Swal.fire({
                    title: 'Menyimpan Perubahan',
                    html: 'Mohon tunggu sebentar...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit form
                this.submit();
            });
        }
        
        // Functions
        function handleFileSelect() {
            if (fileInput.files.length) {
                processFiles(fileInput.files);
            }
        }
        
        function processFiles(files) {
            // Validate files
            const validFiles = validateFiles(files);
            if (!validFiles) return;
            
            // Process each file
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Create preview
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item animate-fade-in';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    
                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-btn';
                    removeBtn.innerHTML = '×';
                    
                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    imagePreview.appendChild(previewItem);
                    
                    // Upload to temp storage
                    uploadTempImage(file, previewItem);
                    
                    // Set up remove button
                    removeBtn.addEventListener('click', function() {
                        const index = Array.from(imagePreview.children).indexOf(previewItem);
                        if (index !== -1 && index < tempImages.length) {
                            removeTempImage(tempImages[index], previewItem);
                        }
                    });
                };
                
                reader.readAsDataURL(file);
            });
            
            // Update progress step
            document.getElementById('step3').classList.add('completed');
        }
        
        function validateFiles(files) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        title: 'Format File Tidak Valid',
                        text: 'Hanya file JPG, PNG, atau GIF yang diperbolehkan',
                        icon: 'error',
                        confirmButtonColor: '#4F46E5'
                    });
                    return false;
                }
                
                if (file.size > maxSize) {
                    Swal.fire({
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file tidak boleh lebih dari 5MB',
                        icon: 'error',
                        confirmButtonColor: '#4F46E5'
                    });
                    return false;
                }
            }
            
            return true;
        }
        
        function uploadTempImage(file, previewElement) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/seller/products/upload-temp-image', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.path) {
                    // Store temp image info
                    const tempImage = {
                        id: data.id,
                        path: data.path,
                        element: previewElement
                    };
                    tempImages.push(tempImage);
                    
                    // Add hidden input
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'images[]';
                    input.value = data.path;
                    input.id = `image-input-${data.id}`;
                    imageInputs.appendChild(input);
                }
            })
            .catch(error => {
                console.error('Error uploading image:', error);
                previewElement.remove();
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal mengupload gambar',
                    icon: 'error',
                    confirmButtonColor: '#4F46E5'
                });
            });
        }
        
        function removeTempImage(tempImage, element) {
            // Remove from DOM
            element.remove();
            
            // Remove hidden input
            const input = document.getElementById(`image-input-${tempImage.id}`);
            if (input) input.remove();
            
            // Remove from temp images array
            tempImages = tempImages.filter(img => img.id !== tempImage.id);
            
            // Delete from server
            fetch('/seller/products/remove-temp-image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ path: tempImage.path })
            });
        }
        
        function validateForm() {
            // Basic validation
            const name = document.getElementById('name').value.trim();
            const category = document.getElementById('category_id').value;
            const price = document.getElementById('price').value;
            const stock = document.getElementById('stock').value;
            const weight = document.getElementById('weight').value;
            const description = document.getElementById('description').value.trim();
            
            if (!name) {
                showError('Nama produk wajib diisi');
                goToStep(1);
                return false;
            }
            
            if (!category) {
                showError('Kategori produk wajib dipilih');
                goToStep(1);
                return false;
            }
            
            if (!price || price < 1000) {
                showError('Harga produk minimal Rp 1.000');
                goToStep(2);
                return false;
            }
            
            if (!stock || stock < 0) {
                showError('Stok produk tidak boleh kosong');
                goToStep(2);
                return false;
            }
            
            if (!weight || weight < 100) {
                showError('Berat produk minimal 100 gram');
                goToStep(2);
                return false;
            }
            
            if (!description || description.length < 20) {
                showError('Deskripsi produk minimal 20 karakter');
                goToStep(2);
                return false;
            }
            
            // Check if at least one image exists (either current or new)
            const currentImages = document.querySelectorAll('#currentImages .preview-item');
            if (currentImages.length === 0 && tempImages.length === 0) {
                showError('Produk harus memiliki minimal satu foto');
                goToStep(3);
                return false;
            }
            
            return true;
        }
        
        function showError(message) {
            Swal.fire({
                title: 'Validasi Gagal',
                text: message,
                icon: 'error',
                confirmButtonColor: '#4F46E5'
            });
        }
        
        // Multi-step form navigation
        window.goToStep = function(step) {
            // Hide all sections
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show the selected section
            document.getElementById(`section${step}`).classList.remove('hidden');
            
            // Update progress steps
            document.querySelectorAll('.progress-step').forEach(progressStep => {
                progressStep.classList.remove('active');
            });
            
            document.getElementById(`step${step}`).classList.add('active');
            
            // Mark previous steps as completed
            for (let i = 1; i < step; i++) {
                document.getElementById(`step${i}`).classList.add('completed');
            }
            
            // Scroll to top of form
            document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Initialize form validation on input
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                // Remove error styling when user starts typing
                this.classList.remove('border-red-500');
                const errorElement = this.nextElementSibling;
                if (errorElement && errorElement.classList.contains('form-error')) {
                    errorElement.textContent = '';
                }
                
                // Update progress steps based on filled inputs
                updateProgressSteps();
            });
        });
        
        function updateProgressSteps() {
            // Step 1 validation
            const nameInput = document.getElementById('name');
            const categoryInput = document.getElementById('category_id');
            
            if (nameInput.value.trim() && categoryInput.value) {
                document.getElementById('step1').classList.add('completed');
            } else {
                document.getElementById('step1').classList.remove('completed');
            }
            
            // Step 2 validation
            const priceInput = document.getElementById('price');
            const stockInput = document.getElementById('stock');
            const weightInput = document.getElementById('weight');
            const descriptionInput = document.getElementById('description');
            
            if (priceInput.value >= 1000 && stockInput.value >= 0 && 
                weightInput.value >= 100 && descriptionInput.value.trim().length >= 20) {
                document.getElementById('step2').classList.add('completed');
            } else {
                document.getElementById('step2').classList.remove('completed');
            }
            
            // Step 3 validation - check if there are any images
            const currentImages = document.querySelectorAll('#currentImages .preview-item');
            if (currentImages.length > 0 || tempImages.length > 0) {
                document.getElementById('step3').classList.add('completed');
            }
        }
        
        // Function to remove existing images
        window.removeCurrentImage = function(imageId, element) {
            Swal.fire({
                title: 'Hapus Gambar?',
                text: 'Gambar akan dihapus dari produk',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/seller/products/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            element.remove();
                            
                            // Check if there are any images left
                            const currentImages = document.querySelectorAll('#currentImages .preview-item');
                            if (currentImages.length === 0 && tempImages.length === 0) {
                                document.getElementById('step3').classList.remove('completed');
                            }
                            
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Gambar berhasil dihapus',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#4F46E5',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.error || 'Gagal menghapus gambar',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#4F46E5'
                            });
                        }
                    });
                }
            });
        };
        
        // Initialize progress steps on page load
        updateProgressSteps();
        
        // Mark all steps as completed initially since we're editing an existing product
        document.getElementById('step1').classList.add('completed');
        document.getElementById('step2').classList.add('completed');
        
        // Check if there are any images
        const currentImages = document.querySelectorAll('#currentImages .preview-item');
        if (currentImages.length > 0) {
            document.getElementById('step3').classList.add('completed');
        }
    });
</script>
@endsection

