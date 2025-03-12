@extends('layouts.seller')

@section('title', 'Kelola Produk')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.min.css">
<style>
    /* Custom styles here */
    /* ... (keep the styles from the previous response) ... */
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Produk</h1>
            <button id="createProductBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">
                Tambah Produk
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table id="productsTable" class="w-full">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded via DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="productForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="productId" name="id">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Tambah Produk</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                            <input type="text" name="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" id="price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" name="stock" id="stock" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                            <input type="number" name="weight" id="weight" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700">Ukuran</label>
                            <input type="text" name="size" id="size" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                            <div id="imageDropzone" class="mt-1 border-2 border-dashed border-gray-300 rounded-md p-6">
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition duration-150 ease-in-out">Upload a file</span>
                                        or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>
<script>
    let productTable;
    let myDropzone;

    $(document).ready(function() {
        // Initialize DataTable
        productTable = $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('seller.products.data') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'price', name: 'price' },
                { data: 'stock', name: 'stock' },
                { data: 'category.name', name: 'category.name' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button onclick="editProduct(${row.id})" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                            <button onclick="deleteProduct(${row.id})" class="text-red-600 hover:text-red-900">Delete</button>
                        `;
                    }
                }
            ]
        });

        // Initialize Select2
        $('#category_id').select2({
            dropdownParent: $('#productModal')
        });

        // Initialize Dropzone
        myDropzone = new Dropzone("#imageDropzone", {
            url: "{{ route('seller.products.upload-image') }}",
            paramName: "file",
            maxFilesize: 10,
            acceptedFiles: "image/*",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Form submission
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('seller.products.store') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    closeModal();
                    productTable.ajax.reload();
                    Swal.fire('Success', 'Product saved successfully', 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error', 'There was an error saving the product', 'error');
                }
            });
        });

        // Open modal for creating new product
        $('#createProductBtn').click(function() {
            $('#modalTitle').text('Tambah Produk');
            $('#productForm')[0].reset();
            $('#productId').val('');
            myDropzone.removeAllFiles();
            openModal();
        });
    });

    function openModal() {
        $('#productModal').removeClass('hidden');
    }

    function closeModal() {
        $('#productModal').addClass('hidden');
    }

    function editProduct(id) {
        $.get("{{ url('seller/products') }}/" + id + "/edit", function(data) {
            $('#modalTitle').text('Edit Produk');
            $('#productId').val(data.id);
            $('#name').val(data.name);
            $('#price').val(data.price);
            $('#stock').val(data.stock);
            $('#category_id').val(data.category_id).trigger('change');
            $('#description').val(data.description);
            $('#weight').val(data.weight);
            $('#size').val(data.size);
            
            // Clear existing files and add current product images
            myDropzone.removeAllFiles();
            if (data.images) {
                data.images.forEach(function(image) {
                    let mockFile = { name: image.name, size: image.size };
                    myDropzone.displayExistingFile(mockFile, image.url);
                });
            }
            
            openModal();
        });
    }

    function deleteProduct(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('seller/products') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        productTable.ajax.reload();
                        Swal.fire('Deleted!', 'Product has been deleted.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'There was an error deleting the product', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection