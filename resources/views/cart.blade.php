<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Keranjang</h2>
    {{-- <form id="barcodeForm">
        <div class="mb-3">
            <label for="barcodeInput">Scan Kode Barang:</label>
            <input type="text" id="barcodeInput" class="form-control" name="barcodeInput" placeholder="Scan barcode..." autocomplete="off">
        </div>
    </form> --}}

    {{-- <form id="addProductForm" onsubmit="return false;">
        <div class="form-group">
            <label for="kodeProduct">Masukkan Kode Barang</label>
            <select id="kodeProduct" class="form-control kode-product-select" name="kode_product" autofocus></select>
        </div>
    </form>
     --}}
    <form id="addProductForm" onsubmit="return false;">
        @csrf
        <div class="mb-3"> 
            <input type="text" class="form-control" id="productCode" name="productCode" placeholder="Masukkan kode produk" onkeyup="handleBarcodeInput(event)" autofocus>
        </div>
    </form>

    <div id="message" class="mt-3"></div>

    <h3 class="mt-5">Item di Keranjang</h3>
    <ul id="cartItems" class="list-group">
        <!-- Items will be dynamically added here -->
    </ul>

    <div class="mt-4">
        <h4>Total: <span id="totalAmount">Rp 0</span></h4>
        <div class="mb-3">
            <label for="paidAmount" class="form-label">Uang Dibayar</label>
            <input type="number" class="form-control" id="paidAmount" name="paid_amount" onkeyup="calculateChange(this.value)">
        </div>
        <h4>Kembalian: <span id="changeAmount">Rp 0</span></h4>
        <button class="btn btn-primary mt-3" onclick="return confirm('yakin ?')?processTransaction():'';" >Proses Transaksi</button>
        <a href="/transactions" class="btn btn-warning mt-3">Lihat Transaksi</a>
        {{-- <a href="/cart/item/all" class="btn btn-secondary mt-3">Hapus Transaksi</a> --}}
        <button id="clearCart" class="btn btn-danger mt-3">Hapus Keranjang</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
        $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// $(document).ready(function() {
//     // Inisialisasi Select2
//     $('#kodeProduct').select2({
//         // Aktifkan pencarian secara otomatis
//         minimumResultsForSearch: -1
//     });

//     // Fokuskan ke Select2 input field setelah inisialisasi
//     setTimeout(function() {
//         $('#kodeProduct').select2('open');
//     }, 100); // Penundaan kecil untuk memastikan Select2 terinisialisasi
// });



// $(document).on('keypress', '#kodeProduct', function(e) {
//     if (e.which == 13) { // KeyCode 13 adalah Enter
//         e.preventDefault(); // Mencegah reload halaman
//     }
// });


// $(document).ready(function() {
//     $('.kode-product-select').select2({
//         placeholder: 'Masukkan atau scan kode barang',
//         ajax: {
//             url: '{{ route("cart.search") }}', // Route ke controller pencarian produk
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     q: params.term // Pencarian berdasarkan input pengguna
//                 };
//             },
//             processResults: function (data) {
//                 return {
//                     results: $.map(data, function (item) {
//                         return {
//                             id: item.id,
//                             text: item.kode_product + ' - ' + item.name
//                         };
//                     })
//                 };
//             },
//             cache: true
//         }
//     });

//     // Fungsi untuk menangani input dari barcode reader
//     $('#kodeProduct').on('select2:select', function (e) {
//         var productId = e.params.data.id;
//         addProductToCart(productId); // Panggil fungsi untuk menambahkan ke keranjang
//     });
// });

// function addProductToCart(productId) {
//     console.log('{{ route("cart.add") }}');

//     $.ajax({
//         url: '{{ route("cart.add") }}',
//         type: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}',
//             product_id: productId
//         },
//         success: function(response) {
//             $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
//             loadCartItems(); // Perbarui tampilan keranjang
//         },
//         // success: function(response) {
//         //     $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
            
//         //     // Reset Select2 setelah input berhasil
//         //     $('#kodeProduct').val(null).trigger('change');

//         //     // Fokuskan kembali ke Select2
//         //     setTimeout(function() {
//         //         $('#kodeProduct').select2('open');
//         //     }, 100);

//         //     loadCartItems();
//         // },

//         error: function(response) {
//             $('#message').text(response.responseJSON.message).removeClass('alert-success').addClass('alert alert-danger');
//         }
//     });
// }


    // let totalAmount = 0;
    // let timeout = null;
    
    // function processProduct(kodeProduct) {

    //     clearTimeout(timeout);
    //     timeout = setTimeout(function() {

    //     if (kodeProduct.length === 0) {
    //         return; // Tidak lakukan apapun jika input kosong
    //     }

    //     $.ajax({
    //         url: '{{ route("cart.add") }}',
    //         type: 'POST',
    //         data: {
    //             _token: '{{ csrf_token() }}',
    //             kode_product: kodeProduct
    //         },
    //         success: function(response) {
    //             $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
    //             $('#kodeProduct').val('');
    //             loadCartItems();
    //         },
    //         error: function(response) {
    //             $('#message').text(response.responseJSON.message).removeClass('alert-success').addClass('alert alert-danger');
    //         }
    //     });
    // }, 300);
    // }

    

// function processProduct(kodeProduct) {
//     clearTimeout(timeout);

//     timeout = setTimeout(function() {
//         if (kodeProduct.length === 0) {
//             return; // Tidak lakukan apapun jika input kosong
//         }

//         $.ajax({
//             url: '{{ route("cart.add") }}',
//             type: 'POST',
//             data: {
//                 _token: '{{ csrf_token() }}',
//                 kode_product: kodeProduct
//             },
//             success: function(response) {
//                 $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
//                 $('#kodeProduct').val('');
//                 loadCartItems();
//             },
//             error: function(response) {
//                 $('#message').text(response.responseJSON.message).removeClass('alert-success').addClass('alert alert-danger');
//             }
//         });
//     }, 500); // 300ms debounce delay
// }

function handleBarcodeInput(event) {
    const kodeProduct = event.target.value;

    // Cek jika keyCode 13 (Enter) ditekan
    if (event.keyCode === 13) {
        event.preventDefault();  // Cegah default action (refresh halaman)
        
        // Panggil fungsi untuk memproses produk
        processProduct(kodeProduct);

        // Kosongkan input setelah proses selesai
        event.target.value = '';
    }
}

function processProduct(kodeProduct) {
        if (kodeProduct.length === 0) {
            return; // Tidak lakukan apapun jika input kosong
        }
        console.log('{{ route("cart.add") }}');

        $.ajax({
            url: '{{ route("cart.add") }}',  // Pastikan URL ini menghasilkan URL yang valid
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                kode_product: kodeProduct
            },
            success: function(response) {
                $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
                $('#kodeProduct').val('');
                loadCartItems();
            },
            error: function(response) {
                $('#message').text(response.responseJSON.message).removeClass('alert-success').addClass('alert alert-danger');
            }
        });
    }


    function loadCartItems() {
        $.ajax({
            url: 'cart/items',
            type: 'GET',
            success: function(data) {
                let items = '';
                totalAmount = 0;
                data.forEach(item => {
                    totalAmount += item.price * item.quantity;
                    items += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.product} - Quantity: ${item.quantity} - Price: Rp ${item.price}
                            <button class="btn btn-danger btn-sm" onclick="removeCartItem(${item.id})">Hapus</button>
                        </li>`;
                });
                $('#cartItems').html(items);
                $('#totalAmount').text(`Rp ${totalAmount.toLocaleString('id-ID')}`);
            }
        });
    }

    function removeCartItem(cartItemId) {
        $.ajax({
            url: `/cart/item/${cartItemId}/remove`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
                loadCartItems();
            },
            error: function(response) {
                $('#message').text(response.responseJSON.message).removeClass('alert-success').addClass('alert alert-danger');
            }
        });
    }

  


$('#clearCart').on('click', function() {
    if (confirm("Anda yakin ingin menghapus semua item dari keranjang?")) {
        $.ajax({
            url: '/cart/remove-all',
            type: 'POST',
            success: function(response) {
                // alert(response.message);
                $('#message').text(response.message).removeClass('alert-danger').addClass('alert alert-success');
                loadCartItems(); // Panggil kembali untuk refresh keranjang
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    }
});

    function calculateChange(paidAmount) {
        const change = paidAmount - totalAmount;
        $('#changeAmount').text(`Rp ${change.toLocaleString('id-ID')}`);
    }

    function processTransaction() {
        const paidAmount = $('#paidAmount').val();

        if (paidAmount < totalAmount) {
            alert('Uang yang dibayar tidak mencukupi total transaksi.');
            return;
        }

        $.ajax({
            url: '{{ route("transaction.process") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                total_amount: totalAmount,
                paid_amount: paidAmount,
                change_amount: paidAmount - totalAmount
            },
            success: function(response) {
                alert('Transaksi berhasil diproses.');
                $('#paidAmount').val('');
                $('#changeAmount').text('Rp 0');
                loadCartItems();
                window.location.href = '/transactions';

            },
            error: function(response) {
                alert('Terjadi kesalahan saat memproses transaksi.');
            }
        });
    }

    // Load cart items when the page loads
    $(document).ready(function() {
        loadCartItems();
    });
</script>
</body>
</html>
