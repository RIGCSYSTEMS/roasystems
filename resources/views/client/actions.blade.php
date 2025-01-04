<div class="btn-group">
    <a href="{{ route('client.show', $cliente->id) }}" class="btn btn-info btn-sm">
        <i class="fa fa-eye"></i> Ver
    </a>
    <a href="{{ route('client.edit', $cliente->id) }}" class="btn btn-primary btn-sm">
        <i class="fa fa-edit"></i> Editar
    </a>
    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarCliente({{ $cliente->id }})">
        <i class="fa fa-trash"></i> Eliminar
    </button>
</div>