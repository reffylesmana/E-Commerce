@extends('layouts.seller')
@section('title', 'Tambah Produk Baru')
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

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            /* Garis abu-abu dasar */
            transform: translateY(-50%);
            z-index: 0;
        }

        .form-progress::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: var(--progress, 0%);
            height: 2px;
            background-color: #4f46e5;
            transform: translateY(-50%);
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }


        .progress-step {
            position: relative;
            z-index: 1;
            /* Pastikan circle di atas garis */
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

    <div class="page-container py-8 px-4">
        <div class="container mx-auto max-w-5xl">
            <div class="mb-6">
                <a href="{{ route('seller.products.index') }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 transition-colors">
                    <i class="iconify mr-1" data-icon="tabler:arrow-left"></i>
                    Kembali ke Daftar Produk
                </a>
            </div>

            <div class="form-card animate-fade-in">
                <div class="form-header">
                    <h1 class="form-title">Tambah Produk Baru</h1>
                    <p class="form-subtitle">Isi semua informasi produk dengan lengkap untuk ditampilkan di toko Anda</p>
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

                    <form id="productForm" action="{{ route('seller.products.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Step 1: Basic Information -->
                        <div class="form-section" id="section1">
                            <h2 class="section-title">
                                <i class="iconify" data-icon="tabler:info-circle"></i>
                                Informasi Dasar
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group input-focus-effect">
                                    <label for="name" class="form-label">Nama Produk <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" class="form-input"
                                        placeholder="Masukkan nama produk" value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                    <p class="form-hint">Gunakan nama yang jelas dan mudah dicari</p>
                                </div>

                                <div class="form-group input-focus-effect">
                                    <label for="category_id" class="form-label">Kategori <span
                                            class="text-red-500">*</span></label>
                                    <select id="category_id" name="category_id" class="form-input form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="button" class="btn btn-primary w-full md:w-auto" data-goto-step="2">
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
                                    <label for="price" class="form-label">Harga (Rp) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" id="price" name="price" min="1000" class="form-input"
                                        placeholder="Contoh: 150000" value="{{ old('price') }}" required>
                                    @error('price')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                    <p class="form-hint">Minimal Rp 1.000</p>
                                </div>

                                <div class="form-group input-focus-effect">
                                    <label for="stock" class="form-label">Stok <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" id="stock" name="stock" min="0" class="form-input"
                                        placeholder="Jumlah stok produk" value="{{ old('stock') }}" required>
                                    @error('stock')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group input-focus-effect">
                                    <label for="weight" class="form-label">Berat (gram) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" id="weight" name="weight" min="100"
                                        class="form-input" placeholder="Contoh: 500" value="{{ old('weight') }}"
                                        required>
                                    @error('weight')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                    <p class="form-hint">Minimal 100 gram</p>
                                </div>

                                <div class="form-group input-focus-effect">
                                    <label for="size" class="form-label">Ukuran</label>
                                    <input type="text" id="size" name="size" class="form-input"
                                        placeholder="Contoh: 10x15x5 cm" value="{{ old('size') }}">
                                    @error('size')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mt-6 input-focus-effect">
                                <label for="description" class="form-label">Deskripsi Produk <span
                                        class="text-red-500">*</span></label>
                                <textarea id="description" name="description" rows="5" class="form-input form-textarea"
                                    placeholder="Jelaskan detail produk Anda secara lengkap" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                                <p class="form-hint">Minimal 20 karakter. Semakin detail deskripsi, semakin menarik bagi
                                    pembeli.</p>
                            </div>

                            <div class="mt-6 flex flex-wrap gap-3">
                                <button type="button" class="btn btn-secondary" data-goto-step="1">
                                    <i class="iconify mr-1" data-icon="tabler:arrow-left"></i>
                                    Kembali
                                </button>
                                <button type="button" class="btn btn-primary" data-goto-step="3">
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
                                <label class="form-label">Unggah Foto Produk <span class="text-red-500">*</span></label>
                                <p class="form-hint mb-3">Foto yang bagus dapat meningkatkan daya tarik produk Anda</p>

                                <div class="upload-container">
                                    <label for="productImages" class="upload-button">
                                        <i class="iconify" data-icon="tabler:upload"></i>
                                        Pilih Foto Produk
                                    </label>
                                    <input type="file" id="productImages" name="product-images[]" multiple
                                        accept="image/*" class="hidden">
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
                                    Simpan Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const fileInput = document.getElementById('productImages');
            const imagePreview = document.getElementById('imagePreview');
            const imageInputs = document.getElementById('imageInputs');
            const form = document.getElementById('productForm');
            let tempImages = [];

            // Step Validation Rules
            const stepValidations = {
                1: [{
                        field: 'name',
                        test: val => val.trim(),
                        error: 'Nama produk wajib diisi'
                    },
                    {
                        field: 'category_id',
                        test: val => val,
                        error: 'Kategori produk wajib dipilih'
                    }
                ],
                2: [{
                        field: 'price',
                        test: val => val >= 1000,
                        error: 'Harga produk minimal Rp 1.000'
                    },
                    {
                        field: 'stock',
                        test: val => val >= 0,
                        error: 'Stok produk tidak boleh kosong'
                    },
                    {
                        field: 'weight',
                        test: val => val >= 100,
                        error: 'Berat produk minimal 100 gram'
                    },
                    {
                        field: 'description',
                        test: val => val.trim().length >= 20,
                        error: 'Deskripsi produk minimal 20 karakter'
                    }
                ],
                3: [{
                    test: () => tempImages.length > 0,
                    error: 'Silakan upload minimal satu foto produk'
                }]
            };

            // Initialize Application
            initEventListeners();
            updateProgressSteps();

            // Main Functions
            function initEventListeners() {
                // Step navigation
                document.querySelectorAll('[data-goto-step]').forEach(button => {
                    button.addEventListener('click', handleStepNavigation);
                });

                // File input
                if (fileInput) {
                    fileInput.addEventListener('change', handleFileSelect);
                }

                // Form submission
                if (form) {
                    form.addEventListener('submit', handleFormSubmit);
                }

                // Real-time validation
                document.querySelectorAll('.form-input').forEach(input => {
                    input.addEventListener('input', handleInputValidation);
                });
            }

            function handleStepNavigation(e) {
                const button = e.currentTarget;
                const targetStep = parseInt(button.getAttribute('data-goto-step'));
                const currentStep = getCurrentStep();

                // Validate when moving forward
                if (targetStep > currentStep && !validateStep(currentStep)) {
                    return;
                }

                goToStep(targetStep);
            }

            function getCurrentStep() {
                const currentSection = document.querySelector('.form-section:not(.hidden)');
                return parseInt(currentSection.id.replace('section', ''));
            }

            function validateStep(step) {
                const validations = stepValidations[step];
                let isValid = true;

                for (const validation of validations) {
                    const field = validation.field;
                    const value = field ? document.getElementById(field).value : null;
                    const testResult = validation.test(field ? value : null);

                    if (!testResult) {
                        showFieldError(validation.error, field);
                        isValid = false;
                    }
                }

                return isValid;
            }

            function goToStep(targetStep) {
                // Update visibility
                document.querySelectorAll('.form-section').forEach(section => {
                    section.classList.add('hidden');
                });
                document.getElementById(`section${targetStep}`).classList.remove('hidden');

                // Update progress indicators
                document.querySelectorAll('.progress-step').forEach(step => {
                    step.classList.remove('active', 'completed');
                });

                for (let i = 1; i <= targetStep; i++) {
                    const stepElement = document.getElementById(`step${i}`);
                    i === targetStep ?
                        stepElement.classList.add('active') :
                        stepElement.classList.add('completed');
                }

                // Scroll to top
                document.querySelector('.form-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                updateProgressSteps();
            }

            // File Handling
            function handleFileSelect() {
                if (fileInput.files.length) {
                    processFiles(fileInput.files);
                }
            }

            function processFiles(files) {
                if (!validateFiles(files)) return;

                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => createImagePreview(e, file);
                    reader.readAsDataURL(file);
                });

                document.getElementById('step3').classList.add('completed');
            }

            function createImagePreview(e, file) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item animate-fade-in';
                previewItem.innerHTML = `
                  <img src="${e.target.result}">
                  <div class="remove-btn">×</div>
              `;

                imagePreview.appendChild(previewItem);
                setupRemoveButton(previewItem);
                uploadTempImage(file, previewItem);
            }

            function setupRemoveButton(previewItem) {
                previewItem.querySelector('.remove-btn').addEventListener('click', () => {
                    const index = Array.from(imagePreview.children).indexOf(previewItem);
                    if (index !== -1 && index < tempImages.length) {
                        removeTempImage(tempImages[index], previewItem);
                    }
                });
            }

            // Validation Helpers
            function validateFiles(files) {
                const maxSize = 5 * 1024 * 1024;
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

            function handleInputValidation(e) {
                const input = e.target;
                input.classList.remove('border-red-500');

                const errorElement = input.nextElementSibling;
                if (errorElement?.classList.contains('form-error')) {
                    errorElement.textContent = '';
                }

                updateProgressSteps();
            }

            function showFieldError(message, fieldId) {
                if (fieldId) {
                    const input = document.getElementById(fieldId);
                    input.classList.add('border-red-500');
                    input.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                showError('Validasi Gagal', message);
            }

            // Form Submission
            function handleFormSubmit(e) {
                e.preventDefault();

                if (!validateStep(1) || !validateStep(2) || !validateStep(3)) {
                    showError('Validasi Gagal', 'Harap lengkapi semua data yang diperlukan');
                    return;
                }

                Swal.fire({
                    title: 'Menyimpan Produk',
                    html: 'Mohon tunggu sebentar...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                this.submit();
            }

            // Image Management
            async function uploadTempImage(file, previewElement) {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                try {
                    const response = await fetch('/seller/products/upload-temp-image', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.path) {
                        tempImages.push({
                            id: data.id,
                            path: data.path,
                            element: previewElement
                        });

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'images[]';
                        input.value = data.path;
                        input.id = `image-input-${data.id}`;
                        imageInputs.appendChild(input);
                    }
                } catch (error) {
                    console.error('Error uploading image:', error);
                    previewElement.remove();
                    showError('Error', 'Gagal mengupload gambar');
                }
            }

            async function removeTempImage(tempImage, element) {
                element.remove();
                document.getElementById(`image-input-${tempImage.id}`)?.remove();
                tempImages = tempImages.filter(img => img.id !== tempImage.id);

                try {
                    await fetch('/seller/products/remove-temp-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            path: tempImage.path
                        })
                    });

                    if (tempImages.length === 0) {
                        document.getElementById('step3').classList.remove('completed');
                    }
                } catch (error) {
                    console.error('Error removing image:', error);
                }
            }

            // UI Helpers
            function showError(title, message) {
                Swal.fire({
                    title,
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#4F46E5'
                });
            }

            function updateProgressSteps() {
                // Step 1
                const step1Valid = stepValidations[1].every(validation =>
                    validation.test(document.getElementById(validation.field).value)
                );
                document.getElementById('step1').classList.toggle('completed', step1Valid);

                // Step 2
                const step2Valid = stepValidations[2].every(validation =>
                    validation.test(document.getElementById(validation.field).value)
                );
                document.getElementById('step2').classList.toggle('completed', step2Valid);

                // Step 3
                const step3Valid = tempImages.length > 0;
                document.getElementById('step3').classList.toggle('completed', step3Valid);

                // Update progress line
                const activeStep = document.querySelector('.progress-step.active');
                const totalSteps = document.querySelectorAll('.progress-step').length;
                const stepNumber = parseInt(activeStep.id.replace('step', ''), 10);

                const targetProgress = ((stepNumber - 1) / (totalSteps - 1)) * 100;
                const currentProgress = parseFloat(getComputedStyle(document.documentElement).getPropertyValue(
                    '--progress')) || 0;

                function animateProgress(start, end, duration) {
                    let startTime = null;

                    function step(timestamp) {
                        if (!startTime) startTime = timestamp;
                        let progress = Math.min((timestamp - startTime) / duration, 1);
                        let easedProgress = start + (end - start) * progress;

                        document.documentElement.style.setProperty('--progress', `${easedProgress}%`);

                        if (progress < 1) {
                            requestAnimationFrame(step);
                        }
                    }

                    requestAnimationFrame(step);
                }

                animateProgress(currentProgress, targetProgress, 200); // Animasi dalam 500ms

            }
        });
    </script>
@endsection
