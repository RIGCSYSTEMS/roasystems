@extends('layouts.app')

@section('title', 'ROASYSTEMS')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-left">
                <h1 class="my-4">Creacion de clientes</h1>
            </div>
            <div class="col-md-4 text-right float-right">
                <a href="{{url('/')}}/client" class="text-primary float-right badge bg-secondary" style="text-decoration: none; color:aliceblue!important">Lista de clientes</a>
            </div>
        </div>
        <form action="/client"method="post">
            @csrf
            <div class="row">
                <div class="col-md-3 my-2">
                    <label><strong style="color: red">*</strong> Nombre de cliente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" id="nombre_de_cliente"
                            name="nombre_de_cliente" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Otros nombres de cliente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right"
                            name="otros_nombres_de_cliente" id="otros_nombres_de_cliente" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Direccion:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="direccion"
                            id="direccion" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Teléfono:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="telefono"
                            id="telefono" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> <strong style="color: red">*</strong> Correo:</label>
                    <div class="input-group">
                        <input type="email" class="form-control form-control-sm float-right" name="email" id="email"
                            required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> <strong style="color: red">*</strong> Lenguaje:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="lenguaje" id="lenguaje"
                            required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Profesión:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="profesion"
                            id="profesion" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Pais de origen:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="pais" id="pais"
                            required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Despacho:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="despacho"
                            id="despacho" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Tipo de expediente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="tipo_de_expediente"
                            id="tipo_de_expediente" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Honorarios:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="honorarios"
                            id="honorarios" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> <strong style="color: red">*</strong> Fecha de apertura:</label>
                    <div class="input-group">
                        <input type="date" class="form-control form-control-sm float-right" name="fecha_de_apertura"
                            id="fecha_de_apertura" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Estatus:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="estatus" id="estatus"
                            required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Observaciones:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="observaciones"
                            id="observaciones" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label> Numero de expediente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="numero_de_expediente"
                            id="numero_de_expediente" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label>Permiso de trabajo:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="permiso_de_trabajo"
                            id="permiso_de_trabajo" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label>IUC:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right" name="iuc"
                            id="iuc" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label>Ubicacion del despacho:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right"
                            name="ubicacion_del_despacho" id="ubicacion_del_despacho" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label>Fecha de cierre:</label>
                    <div class="input-group">
                        <input type="date" class="form-control form-control-sm float-right" name="fecha_de_cierre"
                            id="fecha_de_cierre" required>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                    <label>Cierre de Numero de caja:</label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm float-right"
                            name="cierre_del_numero_de_caja" id="cierre_del_numero_de_caja" required>
                    </div>
                </div>
                <div class="offset-md-5 col-md-2 my-5">
                    <button type="submit" class="btn btn-primary w-100">Crear cliente</button>
                </div>
            </div>
        </form>
    </div>
@endsection