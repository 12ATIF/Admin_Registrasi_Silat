<!-- DataTables Global Configuration -->
<script>
    // Set default configuration for DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-warning" role="status"><span class="visually-hidden">Loading...</span></div></div>',
            search: '<i class="fas fa-search text-muted me-2"></i> Cari:',
            lengthMenu: 'Tampilkan <select class="form-select form-select-sm mx-1">'+
                       '<option value="10">10</option>'+
                       '<option value="25">25</option>'+
                       '<option value="50">50</option>'+
                       '<option value="100">100</option>'+
                       '<option value="-1">Semua</option>'+
                       '</select> data',
            info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data yang tersedia',
            infoFiltered: '(difilter dari _MAX_ total data)',
            loadingRecords: '<div class="spinner-border text-warning" role="status"><span class="visually-hidden">Memuat...</span></div>',
            zeroRecords: '<div class="text-center my-3"><i class="fas fa-folder-open text-warning mb-2" style="font-size: 2rem;"></i><p class="text-muted">Tidak ditemukan data yang sesuai</p></div>',
            emptyTable: '<div class="text-center my-3"><i class="fas fa-inbox text-warning mb-2" style="font-size: 2rem;"></i><p class="text-muted">Tidak ada data yang tersedia</p></div>',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                last: '<i class="fas fa-angle-double-right"></i>'
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
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row my-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-sm btn-success me-1',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-sm btn-danger me-1',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Print',
                className: 'btn btn-sm btn-primary me-1',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns me-1"></i> Kolom',
                className: 'btn btn-sm btn-secondary'
            }
        ]
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
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 300, hide: 100 },
                trigger: 'hover'
            });
        });
        
        // Toggle sidebar (optional feature)
        $('#sidebarToggle').on('click', function() {
            $('.sidebar').toggleClass('sidebar-collapsed');
            $('.content').toggleClass('content-expanded');
        });
        
        // Custom file input label
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
        
        // Select2 initialization (if available)
        if(typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownParent: $(this).parent(),
                width: '100%'
            });
        }
        
        // DatePicker initialization (if available)
        if(typeof $.fn.datepicker !== 'undefined') {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'id'
            });
        }
        
        // Confirmation dialog using SweetAlert2 (if available)
        if(typeof Swal !== 'undefined') {
            $('.delete-confirm').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
</script>