$(document).ready(function() {
    var table = $('#clientes').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        deferLoading: 0,
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
            { data: 'nombre', name: 'nombre_de_cliente' },
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
        // Añadir opciones de paginación personalizadas
        pagingType: "full_numbers",
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        // Añadir botones de exportación
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
});