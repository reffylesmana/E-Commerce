@extends('layouts.app')

@section('content')

    <!-- product list -->
    @if( count($carts) > 0 )
    <div class="container mt-5">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        <div class="row">
            <h2 class="h4 mb-3">Keranjang</h2>
            @foreach ($carts as $cart)
            <div class="col-12 col-md-6">
                <div class="card mb-3 border-light cart-item-card">
                    <div class="row g-0">
                        <!-- Checkbox -->
                        <div class="col-md-3">
                            <img src="{{ $cart->product->photos->first()?->photo ? Storage::url($cart->product->photos->first()->photo) : asset('default-image.jpg') }}"
                                 alt="{{ $cart->product->name }}" class="img-fluid rounded-start">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title">{{ $cart->product->name }}</h5>
                                <p class="card-text m-0 p-0 text-danger">
                                    Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                </p>
                                <p class="card-text mb-2">Stok {{ $cart->product->stock }}</p>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">
                                        <input type="checkbox" class="form-check-input cart-item-checkbox"
                                            data-price="{{ $cart->product->price }}"
                                            id="product-{{ $cart->id }}">
                                    </span>
                                    <input type="number" name="count[]" class="form-control cart-item-count"
                                       value="1" min="1" max="{{ $cart->product->stock }}"
                                       data-price="{{ $cart->product->price }}">
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="fixed-bottom bg-white border-top">
            <div class="container">
                <div class="card border-0">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Checkout</h2>
                        <form action="post">
                            <p id="total-harga" class="text-danger">Rp 0</p>
                            <button type="submit" class="btn btn-dark w-100">Pesan Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

  @endsection
  @section('js')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function () {
        $('.cart-item-count').on('input', function () {
            var max = $(this).attr('max'); // Ambil nilai max dari atribut
            var min = $(this).attr('min'); // Ambil nilai min dari atribut
            var value = parseInt($(this).val(), 10); // Nilai input saat ini

            // Cek jika melebihi max
            if (value > max) {
                $(this).val(max); // Set nilai kembali ke max
                alert(`Jumlah tidak boleh lebih dari ${max}`);
            }

            // Cek jika kurang dari min
            if (value < min) {
                $(this).val(min); // Set nilai kembali ke min
                alert(`Jumlah tidak boleh kurang dari ${min}`);
            }
        });

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function hitungTotalHarga() {
            let total = 0;

            // Loop semua checkbox yang dicentang
            $('.cart-item-checkbox:checked').each(function () {
                const price = parseFloat($(this).data('price')); // Ambil harga produk
                const jumlah = parseInt($(this).closest('.card').find('.cart-item-count').val()) || 1; // Ambil jumlah produk
                total += price * jumlah; // Tambahkan ke total
            });

            // Tampilkan total harga dengan format Rupiah
            $('#total-harga').text(formatRupiah(total));
        }

        // Event listener untuk checkbox
        $('.cart-item-checkbox').on('change', function () {
            const card = $(this).closest('.cart-item-card'); // Ambil card yang bersangkutan

            if ($(this).is(':checked')) {
                card.addClass('border-dark');
            } else {
                card.removeClass('border-dark');
            }

            hitungTotalHarga();
        });

        // Event listener untuk input jumlah produk
        $('.cart-item-count').on('input', function () {
            const max = parseInt($(this).attr('max'));
            if (parseInt($(this).val()) > max) {
                $(this).val(max); // Batasi nilai ke maksimum
                alert('Jumlah tidak boleh melebihi stok produk!');
            }
            hitungTotalHarga();
        });

    });
</script>

  @endsection
