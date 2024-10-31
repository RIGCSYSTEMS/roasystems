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
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 Registros",
            "infoFiltered": "(Filtrado de _MAX_ total Registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
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
        ],
        // Configuración de búsqueda global
        search: {
            smart: true,
            // No especificamos columnas aquí para que busque en todas
        }
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