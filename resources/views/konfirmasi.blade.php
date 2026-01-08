<x-layout>
    @session('message')
        <script type="text/javascript">
            toastr.success("{{ session('message') }}");
        </script>
    @endsession

    <div class="flex items-center justify-center text-2xl font-bold">Terima kasih atas konfirmasinya</div>
</x-layout>
