@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <p class="text-white-50 mb-2 text-uppercase fw-semibold small">Panel</p>
                        <h1 class="display-6 fw-bold mb-2">Resumen del sistema</h1>
                        <p class="mb-0 text-white-50">Accede rápidamente a las acciones más comunes y revisa la actividad reciente.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <div class="d-flex justify-content-lg-end gap-2">
                            <a href="{{ route('alumnos') }}" class="btn btn-light btn-lg px-4">Alumnos</a>
                            <a href="{{ route('centros') }}" class="btn btn-outline-light btn-lg px-4">Centros</a>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3 text-center">
                            <div class="text-white-50 small text-uppercase">Alumnos</div>
                            <div class="fs-4 fw-bold">{{ $totals['alumnos'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3 text-center">
                            <div class="text-white-50 small text-uppercase">Centros</div>
                            <div class="fs-4 fw-bold">{{ $totals['centros'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3 text-center">
                            <div class="text-white-50 small text-uppercase">Materias</div>
                            <div class="fs-4 fw-bold">{{ $totals['materias'] ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3 text-center">
                            <div class="text-white-50 small text-uppercase">Calificaciones</div>
                            <div class="fs-4 fw-bold">{{ $totals['calificaciones'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h2 class="h5 mb-3 text-primary">Acciones rápidas</h2>
                    <div class="d-grid gap-2">
                        <a href="{{ route('alumnos') }}" class="btn btn-primary">Importar / Gestionar Alumnos</a>
                        <a href="{{ route('centros') }}" class="btn btn-outline-primary">Importar / Gestionar Centros</a>
                        <a href="{{ route('calificaciones') }}" class="btn btn-outline-secondary">Añadir Calificación</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h2 class="h5 mb-3 text-primary">Actividad reciente</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="h6">Últimos alumnos</h3>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($recentAlumnos as $al)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $al->nombre }} {{ $al->paterno }} {{ $al->materno }}</div>
                                            <small class="text-muted">Mat: {{ $al->matricula }} — ID: {{ $al->id }}</small>
                                        </div>
                                        <a href="{{ route('alumnos') }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    </li>
                                @endforeach
                                @if($recentAlumnos->isEmpty())
                                    <li class="list-group-item">No hay alumnos recientes.</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6">Últimas calificaciones</h3>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($recentCalificaciones as $c)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $c->alumno }}</div>
                                            <small class="text-muted">{{ $c->materia }} — Prom: {{ $c->promedio }}</small>
                                        </div>
                                        <a href="{{ route('calificaciones') }}" class="btn btn-sm btn-outline-primary">Ver</a>
                                    </li>
                                @endforeach
                                @if($recentCalificaciones->isEmpty())
                                    <li class="list-group-item">No hay calificaciones recientes.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
