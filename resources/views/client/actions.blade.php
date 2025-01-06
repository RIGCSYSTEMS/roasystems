<div class="btn-group d-flex flex-wrap gap-2">
    <a href="{{ route('client.show', $cliente->id) }}" class="btn social-button btn-ver text-white">
        <i class="fa fa-eye"></i> Ver
    </a>
    <a href="{{ route('client.edit', $cliente->id) }}" class="btn social-button btn-editar text-white">
        <i class="fa fa-edit"></i> Editar
    </a>
    <button type="button" class="btn social-button btn-eliminar text-white" onclick="eliminarCliente({{ $cliente->id }})">
        <i class="fa fa-trash"></i> Eliminar
    </button>
</div>