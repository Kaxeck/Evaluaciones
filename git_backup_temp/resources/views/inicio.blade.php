@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Bienvenido</h1>
                    <p class="card-text">Esta es la página de inicio. Navega a los distintos apartados usando la barra superior.</p>
                    <p class="mb-0">Accesos rápidos:</p>
                    <ul>
                        <li><a href="{{ route('centros') }}">Centros</a></li>
                        <li><a href="{{ route('alumnos') }}">Alumnos</a></li>
                        <li><a href="{{ route('calificaciones') }}">Calificaciones</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
