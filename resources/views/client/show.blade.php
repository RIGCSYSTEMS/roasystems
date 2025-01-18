@extends('layouts.app')

@section('title', 'ROASYSTEMS - Perfil del Cliente')

@section('content')
<div id="app">
    <vista-cliente :client-data="{{ json_encode($clientData) }}">
    </vista-cliente>
</div>
@endsection

@push('scripts')
<script>
    const app = new Vue({
        el: '#app',
        components: {
            'vista-cliente': require('./components/Vista.vue').default
        }
    });
</script>
@endpush

