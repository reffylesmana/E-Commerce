@extends('layouts.seller')

@section('title', 'Edit Artikel - TechnoShop Seller')
@section('description', 'Edit artikel blog untuk toko Anda di TechnoShop')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="page-header">
        <div class="mb-4"></div>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-md">
        <div class="w-full overflow-x-auto p-6">
            <form action="{{ route('seller.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Blog Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}" required
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Slug
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $blog->slug) }}"
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk menghasilkan slug otomatis dari judul.</p>
                        @error('slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Gambar Utama
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="featured_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="image-placeholder" style="{{ $blog->featured_image ? 'display: none;' : '' }}">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (Maks. 2MB)</p>
                                </div>
                                <div id="image-preview" style="{{ $blog->featured_image ? '' : 'display: none;' }}">
                                    <img id="preview-image" src="{{ $blog->featured_image ? Storage::url($blog->featured_image) : '#' }}" alt="Preview" class="h-64 object-contain p-4">
                                </div>
                                <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>
                        @error('featured_image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Ringkasan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="excerpt" id="excerpt" rows="3" required
                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Ringkasan singkat yang akan ditampilkan di halaman daftar artikel.</p>
                        @error('excerpt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                            Konten <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="content" rows="10"
                            class="hidden">{{ old('content', $blog->content) }}</textarea>
                        <div id="editor-container" class="prose max-w-none"></div>
                        @error('content')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publication Settings -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Pengaturan Publikasi</h3>
                        
                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $blog->is_published) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="is_published" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Publikasikan
                            </label>
                        </div>
                        
                        <div id="published_at_container" class="{{ old('is_published', $blog->is_published) ? '' : 'hidden' }}">
                            <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-2">
                                Tanggal Publikasi
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-500 focus:ring-opacity-40">
                            <p class="text-xs text-gray-500 mt-1">Biarkan kosong untuk menggunakan waktu saat ini.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('seller.blogs.index') }}" class="px-6 py-2 leading-5 text-gray-700 transition-colors duration-200 transform bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:bg-gray-300 mr-4">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Preview
        const imageInput = document.getElementById('featured_image');
        const imagePlaceholder = document.getElementById('image-placeholder');
        const imagePreview = document.getElementById('image-preview');
        const previewImage = document.getElementById('preview-image');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Slug Generator
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('blur', function() {
            if (!slugInput.value) {
                slugInput.value = titleInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
            }
        });

        // Publication Settings
        const isPublishedCheckbox = document.getElementById('is_published');
        const publishedAtContainer = document.getElementById('published_at_container');

        isPublishedCheckbox.addEventListener('change', function() {
            publishedAtContainer.classList.toggle('hidden', !this.checked);
        });

        // CKEditor Initialization
        let editor;

        ClassicEditor
            .create(document.querySelector('#editor-container'), {
                initialData: document.querySelector('#content').value,
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: ' Heading 3', class: 'ck-heading_heading3' }
                    ]
                },
                toolbar: [
                    'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'
                ]
            })
            .then(newEditor => {
                editor = newEditor;

                // Update the hidden textarea on form submission
                document.querySelector('form').addEventListener('submit', function(e) {
                    const contentData = editor.getData();
                    document.querySelector('#content').value = contentData;

                    if (!contentData.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Konten kosong',
                            text: 'Harap isi konten artikel sebelum menyimpan'
                        });
                    }
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>

@endsection