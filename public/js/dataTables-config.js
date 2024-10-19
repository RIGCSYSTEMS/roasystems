$(document).ready(function() {
    // Verifica si la tabla ya ha sido inicializada
    if ($.fn.DataTable.isDataTable('#clientes')) {
        // Si ya está inicializada, destrúyela primero
        $('#clientes').DataTable().destroy();
    }

    var table = $('#clientes').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        stateSave: true,
        ajax: {
            url: "/client/lista/getDataClientes",
            type: 'GET',
            data: function(d) {
                d.fechas = $("#fechas").val();
                d.search_active = d.search.value.length >= 3 ? 1 : 0;
            },
        },
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'nombre',
                name: 'nombre_de_cliente',
                render: function(data, type, row) {
                    return '<a href="/client/' + row.id + '" class="client-link">' + data + '</a>';
                }
            },
            { data: 'telefono', name: 'telefono' },
            { data: 'correo', name: 'email' },
            { data: 'direccion', name: 'direccion' },
            { data: 'pais', name: 'pais' },
            { data: 'fecha_apertura', name: 'fecha_de_apertura' },
            { data: 'fecha_cierre', name: 'fecha_de_cierre' },
            { data: 'estatus', name: 'estatus' },
            { 
                data: 'acciones', 
                name: 'acciones', 
                orderable: false, 
                searchable: false 
            },
        ],
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
            emptyTable: 'Realice una búsqueda para ver los resultados'
        },
        searchDelay: 500,
        drawCallback: function(settings) {
            $('#logo-background').toggle(!(settings.json && settings.json.recordsTotal > 0 && settings.json.search_active));
        },
        pagingType: "full_numbers",
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#clientes_filter input').on('input', function() {
        if (this.value.length >= 3 || this.value.length === 0) {
            table.search(this.value).draw();
        }
    });

    // Función para limpiar la búsqueda y recargar la tabla
    function clearSearchAndReloadTable() {
        table.search('').draw();
    }

    // Evento para manejar el clic en el botón "Volver a la lista de clientes"
    $(document).on('click', '#volverListaClientes', function(e) {
        sessionStorage.setItem('clearSearch', 'true');
    });

    // Código para ejecutar cuando la página de la lista de clientes se carga
    if (sessionStorage.getItem('clearSearch') === 'true') {
        console.log('Limpiando búsqueda...');
        clearSearchAndReloadTable();
        sessionStorage.removeItem('clearSearch');
        console.log('Búsqueda limpiada');
    }

    // Manejo de clic en los enlaces de clientes
    $('#clientes').on('click', '.client-link', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.location.href = url;
    });
});
