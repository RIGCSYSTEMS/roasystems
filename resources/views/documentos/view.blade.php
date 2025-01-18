{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Visualizar Documento</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $documento->nombre_de_documento }}</h5>
            <p>Tipo: {{ $documento->tipo_de_documento }}</p>
            <p>Fecha de subida: {{ $documento->created_at->format('d/m/Y H:i') }}</p>
            
            @if($documento->tipo_de_documento == 'PDF')
                <div class="ratio ratio-16x9">
                    <iframe src="{{ asset($documento->imagen_url) }}" width="100%" height="600px" style="border: none;"></iframe>
                </div>
                <p class="mt-3">
                    <a href="{{ asset($documento->imagen_url) }}" download class="btn btn-primary">Descargar PDF</a>
                </p>
            @else
                <img src="{{ asset($documento->imagen_url) }}" alt="{{ $documento->nombre_de_documento }}" class="img-fluid">
            @endif
            <div class="mb-3 mt-3">
                <label for="observaciones" class="form-label">OBSERVACIONES</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="4" readonly>{{ $documento->observaciones }}</textarea>
            </div>
        </div>
    </div>
    <a href="{{ route('client.documentos', $documento->client_id) }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.querySelector('iframe');
    if (iframe) {
        iframe.onload = function() {
            if (iframe.contentDocument.body.innerHTML === "") {
                iframe.style.display = 'none';
                const errorMessage = document.createElement('p');
                errorMessage.textContent = 'No se puede mostrar el PDF directamente. Por favor, use el botón de descarga.';
                errorMessage.className = 'alert alert-warning';
                iframe.parentNode.insertBefore(errorMessage, iframe);
            }
        };
    }
});
</script>
@endsection --}}