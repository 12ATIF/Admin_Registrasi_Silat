<!-- DataTables Global Configuration -->
<script>
    // Set default configuration for DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data yang tersedia',
            infoFiltered: '(difilter dari _MAX_ total data)',
            loadingRecords: 'Memuat...',
            zeroRecords: 'Tidak ditemukan data yang sesuai',
            emptyTable: 'Tidak ada data yang tersedia',
            paginate: {
                first: 'Pertama',
                previous: 'Sebelumnya',
                next: 'Selanjutnya',
                last: 'Terakhir'
            },
            aria: {
                sortAscending: ': aktifkan untuk mengurutkan kolom secara menaik',
                sortDescending: ': aktifkan untuk mengurutkan kolom secara menurun'
            }
        },
        responsive: true,
        processing: true,
        autoWidth: false,
        stateSave: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Semua']],
    });
    
    // Ajax setup for CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        // Set timeout for alert messages
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Enable all tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>