@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Honorarios de {{$client->nombre_de_cliente}}</h1>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Honorarios</h5>
                    <h3>${{number_format($totalHonorarios, 2)}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Pagado</h5>
                    <h3>${{number_format($totalPagado, 2)}}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning">
                <div class="card-body text-center">
                    <h5 class="card-title">Saldo Pendiente</h5>
                    <h3>${{number_format($saldoPendiente, 2)}}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Detalle por Expediente</h3>
        </div>
        <div class="card-body">
            @foreach($expedientes as $expediente)
                @if($expediente->honorarios)
                    <div class="border-bottom mb-4 pb-4">
                        <h4>Expediente: {{$expediente->numero_de_dossier}}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Monto Total:</strong> ${{number_format($expediente->honorarios->monto_total_expediente, 2)}}</p>
                                <p><strong>Monto Adicional:</strong> ${{number_format($expediente->honorarios->monto_adicional, 2)}}</p>
                                <p><strong>Total a Pagar:</strong> ${{number_format($expediente->honorarios->monto_total_a_pagar, 2)}}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total Abonado:</strong> ${{number_format($expediente->honorarios->total_abonos, 2)}}</p>
                                <p><strong>Saldo Pendiente:</strong> ${{number_format($expediente->honorarios->saldo_pendiente, 2)}}</p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge bg-{{$expediente->honorarios->saldo_pendiente > 0 ? 'warning' : 'success'}}">
                                        {{$expediente->honorarios->saldo_pendiente > 0 ? 'Pendiente' : 'Pagado'}}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if($expediente->honorarios->abonos->count() > 0)
                            <h5 class="mt-3">Historial de Abonos</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Monto</th>
                                            <th>Método de Pago</th>
                                            <th>Factura</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expediente->honorarios->abonos as $abono)
                                            <tr>
                                                <td>{{$abono->fecha->format('d/m/Y')}}</td>
                                                <td>${{number_format($abono->monto, 2)}}</td>
                                                <td>{{$abono->metodo_pago}}</td>
                                                <td>{{$abono->factura ?? 'N/A'}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection