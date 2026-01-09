<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/nhlogo.png') }}" type="image/png">
    <title>Manajemen PO</title>
    
    {{-- jquery cdn --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- select2 cdn --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- tailwind cdn --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- flowbite cdn --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />

    {{-- toastr --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- select2 style --}}
    <style>
        .select2-container .select2-selection--single {
            height: 2.5rem;
            /* Tinggi dropdown */
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            padding-top: 0.4rem;
            padding-bottom: 0.6rem;
            /* Padding di dalam select */
            border: 1px solid #ccc;
            border-width: 0px;
            /* Border */
            border-radius: 0.375rem;
            /* Border radius */
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(209 213 219 / var(--tw-ring-opacity));
            --tw-ring-inset: inset;
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            /* ring */
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
            --tw-text-opacity: 1;
            color: rgb(17 24 39 / var(--tw-text-opacity));
            /* shadow */
            width: 100%;
            display: block;
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #333;
            font-size: 12px
                /* Warna teks */
                /* line-height: 38px; */
                /* Vertikal align text */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            /* Vertikal align arrow */
        }

        .select2-dropdown {
            border-radius: 5px;
            /* Border radius pada dropdown */
            background-color: #fff;
            /* Warna background dropdown */
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
            /* Warna opsi yang di-hover */
            color: white;
            /* Warna teks opsi yang di-hover */
        }
    </style>

    {{-- custom scrollbar style --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            /* Width of the vertical scrollbar */
            height: 8px;
            /* Height of the horizontal scrollbar */
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #099AA7;
            /* Thumb color */
            border-radius: 10px;
            /* Rounded corners for the thumb */
            border: 2px solid #fff;
            /* Border around the thumb */
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Track color */
            border-radius: 10px;
        }
    </style>

    {{-- sweetalert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .swal2-popup .swal2-confirm-btn {
            background-color: #099AA7 !important;
            color: #ffffff !important;
            border: 1px solid #099AA7 !important;
        }

        .swal2-popup .swal2-confirm-btn:hover {
            background-color: #087f8a !important;
            border-color: #087f8a !important;
        }

        .swal2-popup .swal2-cancel-btn {
            background-color: #e5e7eb !important;
            color: #374151 !important;
            border: 1px solid #d1d5db !important;
        }

        .swal2-popup .swal2-cancel-btn:hover {
            background-color: #d1d5db !important;
            color: #1f2937 !important;
        }

        .swal2-popup .swal2-confirm-btn:focus,
        .swal2-popup .swal2-cancel-btn:focus {
            box-shadow: 0 0 0 3px rgba(9, 154, 167, 0.25) !important;
        }
    </style>
</head>

<body class="h-80 bg-white overflow-hidden">
    @auth
        @include('components.sidebar')
    @endauth
    @include('components.navbar')
    @include('components.loading-modal')

    <main class="mt-20 mx-6 md:mx-10 pb-10">
        {{ $slot }}
    </main>
</body>

{{-- rupiah formatter --}}
<script>
    // Fungsi untuk memformat angka menjadi format dengan titik
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Ambil semua elemen dengan kelas 'format-rupiah'
    const inputs = document.querySelectorAll(".format-rupiah");

    // Loop setiap elemen dan format value-nya
    inputs.forEach(input => {
        const valueAsli = input.value; // Ambil value asli
        input.value = formatRupiah(valueAsli); // Format angka
    });
</script>

{{-- loading indicator modal trigger script --}}
<script>
    function showLoadingModal() {
        document.getElementById('loading-modal').classList.remove('hidden');
    }
</script>

{{-- flowbite cdn --}}
<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

{{-- select2 cdn --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- sweetalert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>
