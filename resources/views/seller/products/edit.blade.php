@extends('layouts.seller')
@section('title', 'Edit Produk')

@section('content')
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
    
    /* Enhanced Image upload styling */
    .upload-zone {
        position: relative;
        border: 2px dashed #e5e7eb;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        background-color: #f9fafb;
        cursor: pointer;
        overflow: hidden;
    }
    
    .upload-zone:hover {
        border-color: #4f46e5;
        background-color: rgba(79, 70, 229, 0.05);
    }
    
    .upload-zone.drag-over {
        border-color: #4f46e5;
        background-color: rgba(79, 70, 229, 0.1);
        transform: scale(1.01);
    }
    
    .upload-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        border-radius: 9999px;
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .upload-zone:hover .upload-icon {
        transform: scale(1.1);
        background-color: rgba(79, 70, 229, 0.2);
    }
    
    .upload-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
    }
    
    .upload-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }
    
    .upload-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        background-color: #4f46e5;
        color: white;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);
    }
    
    .upload-button:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
    }
    
    .upload-formats {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 1rem;
    }
    
    /* Image preview grid */
    .image-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(6rem, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .preview-item {
        position: relative;
        aspect-ratio: 1/1;
        border-radius: 0.75rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .preview-item:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .preview-item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.3), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .preview-item:hover::before {
        opacity: 1;
    }
    
    .remove-btn {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        width: 2rem;
        height: 2rem;
        background-color: #ef4444;
        color: white;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.25rem;
        opacity: 0;
        transform: translateY(0.5rem);
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .preview-item:hover .remove-btn {
        opacity: 1;
        transform: translateY(0);
    }
    
    .remove-btn:hover {
        background-color: #dc2626;
        transform: scale(1.1);
    }
    
    /* Upload progress */
    .upload-progress-container {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 0.25rem;
        background-color: rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .upload-progress-bar {
        height: 100%;
        background-color: #4f46e5;
        width: 0;
        transition: width 0.3s ease;
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
    
    /* Empty state */
    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #6b7280;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
                <form id="productForm" action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                    </div>
                    
                    <div class="form-section" id="section2">
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
                                    <label for="weight" class="form-label">Berat (kg) <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="weight" name="weight" class="form-input"
                                        placeholder="Contoh: 1, 2, 2.5" value="{{ old('weight', $product->weight) }}" required>
                                    @error('weight')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                    <p class="form-hint">Masukkan berat dalam kilogram (kg). Contoh: 1, 2, 2.5</p>
                                </div>
                            
                            <div class="form-group input-focus-effect">
                                <label for="size" class="form-label">Ukuran</label>
                                <input type="text" id="size" name="size" class="form-input" placeholder="Contoh: 10x15x5 cm, 14 inch, XL" value="{{ old('size', $product->size) }}">
                                @error('size')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-hint">Format bebas: dimensi, inch, ukuran pakaian, dll</p>
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
                    </div>
                    
                    <div class="form-section" id="section3">
                        <h2 class="section-title">
                            <i class="iconify" data-icon="tabler:photo"></i>
                            Foto Produk
                        </h2>
                        
                        <div class="form-group">
                            <label class="form-label">Foto Produk Saat Ini</label>
                            <div class="image-preview" id="currentImages">
                                @if($product->photos->isNotEmpty())
                                    @foreach($product->photos as $photo)
                                        <div class="preview-item" data-image-id="{{ $photo->id }}">
                                            <img src="{{ Storage::url($photo->photo) }}" alt="{{ $product->name }}">
                                            <div class="remove-btn" onclick="removeCurrentImage({{ $photo->id }}, this.parentNode)">
                                                <i class="iconify" data-icon="tabler:trash"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <i class="iconify text-4xl mb-2" data-icon="tabler:photo-off"></i>
                                        <p>Tidak ada foto produk</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group mt-6">
                            <label class="form-label">Tambah Foto Baru</label>
                            <p class="form-hint mb-3">Foto yang bagus dapat meningkatkan daya tarik produk Anda</p>
                            
                            <div id="upload-zone" class="upload-zone">
                                <div class="upload-icon">
                                    <i class="iconify text-3xl" data-icon="tabler:cloud-upload"></i>
                                </div>
                                <h3 class="upload-title">Tambahkan Foto Produk</h3>
                                <p class="upload-subtitle">Seret dan lepaskan foto di sini, atau klik untuk memilih</p>
                                <button type="button" class="upload-button" id="browse-button">
                                    <i class="iconify mr-2" data-icon="tabler:photo-plus"></i>
                                    Pilih Foto
                                </button>
                                <p class="upload-formats">Format: JPG, PNG, GIF (Maks. 5MB)</p>
                                <input type="file" id="productImages" name="product-images[]" multiple accept="image/*" class="hidden">
                            </div>
                            
                            <div id="imagePreview" class="image-preview mt-6"></div>
                            <div id="imageInputs" class="hidden"></div>
                            
                            @error('images')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">
                                <i class="iconify mr-1" data-icon="tabler:x"></i>
                                Batal
                            </a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables
        const fileInput = document.getElementById('productImages');
        const uploadZone = document.getElementById('upload-zone');
        const browseButton = document.getElementById('browse-button');
        const imagePreview = document.getElementById('imagePreview');
        const imageInputs = document.getElementById('imageInputs');
        const form = document.getElementById('productForm');
        const statusToggle = document.getElementById('is_active');
        const statusLabel = document.getElementById('status-label');
        let tempImages = [];
        
        // Check if there are validation errors and go to the appropriate section
        @if(session('error_section'))
            // Find the section with the error and show it
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById('section{{ session('error_section') }}').classList.remove('hidden');
        @endif
        
        // Check for PHP validation errors
        @if($errors->any())
            const errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push("{{ $error }}");
            @endforeach
            
            Swal.fire({
                title: 'Validasi Gagal',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonColor: '#4F46E5'
            });
        @endif
        
        // Initialize Application
        initEventListeners();
        
        // Main Functions
        function initEventListeners() {
            // Status toggle
            if (statusToggle && statusLabel) {
                statusToggle.addEventListener('change', function() {
                    statusLabel.textContent = this.checked ? 'Aktif' : 'Nonaktif';
                });
            }
            
            // File input
            if (fileInput) {
                fileInput.addEventListener('change', handleFileSelect);
            }
            
            // Browse button
            if (browseButton) {
                browseButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent event bubbling
                    fileInput.click();
                });
            }
            
            // Upload zone
            if (uploadZone) {
                // Drag and drop events
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, preventDefaults, false);
                });
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, highlight, false);
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadZone.addEventListener(eventName, unhighlight, false);
                });
                
                uploadZone.addEventListener('drop', handleDrop, false);
                uploadZone.addEventListener('click', function(e) {
                    // Only trigger file input if not clicking on the browse button
                    if (e.target !== browseButton && !browseButton.contains(e.target)) {
                        fileInput.click();
                    }
                });
            }
            
            // Form submission
            if (form) {
                form.addEventListener('submit', handleFormSubmit);
            }
        }
        
        // File Handling
        function handleFileSelect(e) {
            if (fileInput.files.length) {
                processFiles(fileInput.files);
            }
        }
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                fileInput.files = files;
                processFiles(files);
            }
        }
        
        function processFiles(files) {
            if (!validateFiles(files)) return;
            
            // Show loading state
            uploadZone.classList.add('animate-pulse');
            
            // Process each file
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => createImagePreview(e, file);
                reader.readAsDataURL(file);
            });
            
            uploadZone.classList.remove('animate-pulse');
        }
        
        function createImagePreview(e, file) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item animate-fade-in';
            
            // Create image element
            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = file.name;
            
            // Create remove button
            const removeBtn = document.createElement('div');
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '<i class="iconify" data-icon="tabler:trash"></i>';
            
            // Create progress bar
            const progressContainer = document.createElement('div');
            progressContainer.className = 'upload-progress-container';
            
            const progressBar = document.createElement('div');
            progressBar.className = 'upload-progress-bar';
            progressContainer.appendChild(progressBar);
            
            // Append elements
            previewItem.appendChild(img);
            previewItem.appendChild(removeBtn);
            previewItem.appendChild(progressContainer);
            imagePreview.appendChild(previewItem);
            
            // Setup remove button
            removeBtn.addEventListener('click', () => {
                const index = Array.from(imagePreview.children).indexOf(previewItem);
                if (index !== -1 && index < tempImages.length) {
                    removeTempImage(tempImages[index], previewItem);
                }
            });
            
            // Upload to server
            uploadTempImage(file, previewItem, progressBar);
        }
        
        // Validation Helpers
        function validateFiles(files) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            for (const file of files) {
                if (!validTypes.includes(file.type)) {
                    showError('Format File Tidak Valid', 'Hanya file JPG, PNG, atau GIF yang diperbolehkan');
                    return false;
                }
                
                if (file.size > maxSize) {
                    showError('Ukuran File Terlalu Besar', 'Ukuran file tidak boleh lebih dari 5MB');
                    return false;
                }
            }
            
            return true;
        }
        
        // Form Submission
        function handleFormSubmit(e) {
            e.preventDefault();
            
            // Basic validation
            const name = document.getElementById('name').value.trim();
            const category = document.getElementById('category_id').value;
            const price = document.getElementById('price').value;
            const stock = document.getElementById('stock').value;
            const weight = document.getElementById('weight').value;
            const description = document.getElementById('description').value.trim();
            
            // Collect validation errors
            const errors = [];
            
            if (!name) errors.push('Nama produk wajib diisi');
            if (!category) errors.push('Kategori produk wajib dipilih');
            if (!price || price < 1000) errors.push('Harga produk minimal Rp 1.000');
            if (!stock || stock < 0) errors.push('Stok produk tidak boleh kosong');
            if (!weight || weight < 1) errors.push('Berat produk wajib diisi (minimal 1 gram)');
            if (!description || description.length < 20) errors.push('Deskripsi produk minimal 20 karakter');
            
            // Check if at least one image exists (either current or new)
            const currentImages = document.querySelectorAll('#currentImages .preview-item');
            if (currentImages.length === 0 && tempImages.length === 0) {
                errors.push('Produk harus memiliki minimal satu foto');
            }
            
            if (errors.length > 0) {
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: errors.join('<br>'),
                    icon: 'error',
                    confirmButtonColor: '#4F46E5'
                });
                return;
            }
            
            // Show detailed debug info before submitting
            console.log('Submitting form with data:', {
                name,
                category,
                price,
                stock,
                weight,
                description,
                images: tempImages.map(img => img.path)
            });
            
            Swal.fire({
                title: 'Menyimpan Perubahan',
                html: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            this.submit();
        }
        
        // Image Management
        async function uploadTempImage(file, previewElement, progressBar) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            try {
                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    if (progress <= 90) {
                        progressBar.style.width = `${progress}%`;
                    }
                }, 100);
                
                const response = await fetch('/seller/products/upload-temp-image', {
                    method: 'POST',
                    body: formData
                });
                
                clearInterval(interval);
                progressBar.style.width = '100%';
                
                setTimeout(() => {
                    previewElement.querySelector('.upload-progress-container').style.opacity = '0';
                }, 500);
                
                const data = await response.json();
                
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
                } else if (data.error) {
                    showError('Error', data.error);
                    previewElement.remove();
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                previewElement.remove();
                showError('Error', 'Gagal mengupload gambar: ' + error.message);
            }
        }
        
        async function removeTempImage(tempImage, element) {
            // Add removal animation
            element.style.transform = 'scale(0.8)';
            element.style.opacity = '0';
            
            setTimeout(async () => {
                // Remove from DOM
                element.remove();
                
                // Remove hidden input
                document.getElementById(`image-input-${tempImage.id}`)?.remove();
                
                // Remove from temp images array
                tempImages = tempImages.filter(img => img.id !== tempImage.id);
                
                try {
                    const response = await fetch('/seller/products/remove-temp-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            path: tempImage.path
                        })
                    });
                    
                    const data = await response.json();
                    if (!data.success && data.error) {
                        console.error('Error removing image:', data.error);
                    }
                } catch (error) {
                    console.error('Error removing image:', error);
                    showError('Error', 'Gagal menghapus gambar: ' + error.message);
                }
            }, 300);
        }
        
        // UI Helpers
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            uploadZone.classList.add('drag-over');
        }
        
        function unhighlight() {
            uploadZone.classList.remove('drag-over');
        }
        
        function showError(title, message) {
            Swal.fire({
                title,
                text: message,
                icon: 'error',
                confirmButtonColor: '#4F46E5'
            });
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
                    // Add removal animation
                    element.style.transform = 'scale(0.8)';
                    element.style.opacity = '0';
                    
                    setTimeout(() => {
                        fetch(`/seller/products/images/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                element.remove();
                                
                                // Check if there are any images left
                                const currentImages = document.querySelectorAll('#currentImages .preview-item');
                                if (currentImages.length === 0 && tempImages.length === 0) {
                                    // Show empty state if no images left
                                    const emptyState = document.createElement('div');
                                    emptyState.className = 'empty-state';
                                    emptyState.innerHTML = `
                                        <i class="iconify text-4xl mb-2" data-icon="tabler:photo-off"></i>
                                        <p>Tidak ada foto produk</p>
                                    `;
                                    document.getElementById('currentImages').appendChild(emptyState);
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
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus gambar',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#4F46E5'
                            });
                        });
                    }, 300);
                }
            });
        };
    });
</script>
@endsection

