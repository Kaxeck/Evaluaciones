@extends('layouts.app')

@section('title', 'Alumnos')

@section('content')
<section id="alumnos" class="mb-5">
    <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <p class="text-white-50 mb-2 text-uppercase fw-semibold small">Gestión escolar</p>
                <h1 class="display-6 fw-bold mb-2">Padrón de Alumnos</h1>
                <p class="mb-0 text-white-50">Gestion de la base de datos de estudiantes.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="btn btn-light btn-lg px-4" data-bs-toggle="modal" data-bs-target="#modalregistar">
                    <i class="fa-solid fa-user-plus me-2"></i>Añadir Alumno
                </button>
            </div>
        </div>

        <div class="row g-3 mt-4">
            <div class="col-md-6 col-lg-3">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3">
                    <div class="text-white-50 small text-uppercase">Alumnos</div>
                    <div class="fs-4 fw-bold">{{ $datos->total() ?? count($datos) }}</div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3">
                    <div class="text-white-50 small text-uppercase">Centros</div>
                    <div class="fs-4 fw-bold">{{ count($centros) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script>
        var res = function() {
            var not = confirm("¿Desea eliminar este alumno?");
            return not;
        }
    </script>

    <!-- Modal de registro -->
    <div class="modal fade" id="modalregistar" tabindex="-1" aria-labelledby="modalAlumnoCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="modalAlumnoCrearLabel">Añadir datos del alumno</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('alumnos.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Centro</label>
                                <select class="form-select" name="txtcentro_id">
                                    <option value="">Selecciona un centro</option>
                                    @foreach($centros as $centro)
                                        <option value="{{ $centro->id }}">{{ $centro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Matrícula</label>
                                <input type="text" class="form-control" name="txtmatricula">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Estatus</label>
                                <input type="text" class="form-control" name="txtestatus">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="txtnombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" name="txtpaterno">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" name="txtmaterno">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Género</label>
                                <input type="text" class="form-control" name="txtgenero">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Generación</label>
                                <input type="text" class="form-control" name="txtgeneracion">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="txtfecha_nacimiento">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Municipio de Residencia</label>
                                <input type="text" class="form-control" name="txtmunicipio_residencia">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">País de Nacimiento</label>
                                <input type="text" class="form-control" name="txtpais_nacimiento">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Añadir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
            <div>
                <h2 class="h5 mb-1 text-primary">Listado de alumnos</h2>
                <div class="text-muted small">Administración de registros con acciones de edición y eliminación.</div>
            </div>
        </div>
        <!-- Tabla de datos -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Centro</th>
                        <th scope="col">Matrícula</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Paterno</th>
                        <th scope="col">Materno</th>
                        <th scope="col">Género</th>
                        <th scope="col">Generación</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">País</th>
                        <th scope="col">F. Nacimiento</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->centro_nombre }}</td>
                            <td>{{ $item->matricula }}</td>
                            <td>{{ $item->estatus }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->paterno }}</td>
                            <td>{{ $item->materno }}</td>
                            <td>{{ $item->genero }}</td>
                            <td>{{ $item->generacion }}</td>
                            <td>{{ $item->municipio_residencia }}</td>
                            <td>{{ $item->pais_nacimiento }}</td>
                            <td>{{ $item->fecha_nacimiento }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalditar{{ $item->id }}" class="btn btn-outline-warning btn-sm me-1">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <a href="{{ route('alumnos.destroy', ['id' => $item->id]) }}" onclick="return res()" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal de modificar datos -->
                        <div class="modal fade" id="modalditar{{ $item->id }}" tabindex="-1" aria-labelledby="modalAlumnoEditarLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white">
                                        <h1 class="modal-title fs-5" id="modalAlumnoEditarLabel{{ $item->id }}">Modificar datos del alumno</h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <form action="{{ route('alumnos.update') }}" method="POST">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Código</label>
                                                    <input type="text" class="form-control" name="txtcodigo" value="{{ $item->id }}" readonly>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Centro</label>
                                                    <select class="form-select" name="txtcentro_id">
                                                        @foreach($centros as $centro)
                                                            <option value="{{ $centro->id }}" {{ $centro->id == $item->centro_id ? 'selected' : '' }}>{{ $centro->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Matrícula</label>
                                                    <input type="text" class="form-control" name="txtmatricula" value="{{ $item->matricula }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Estatus</label>
                                                    <input type="text" class="form-control" name="txtestatus" value="{{ $item->estatus }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nombre</label>
                                                    <input type="text" class="form-control" name="txtnombre" value="{{ $item->nombre }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Apellido Paterno</label>
                                                    <input type="text" class="form-control" name="txtpaterno" value="{{ $item->paterno }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Apellido Materno</label>
                                                    <input type="text" class="form-control" name="txtmaterno" value="{{ $item->materno }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Género</label>
                                                    <input type="text" class="form-control" name="txtgenero" value="{{ $item->genero }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Generación</label>
                                                    <input type="text" class="form-control" name="txtgeneracion" value="{{ $item->generacion }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Fecha de Nacimiento</label>
                                                    <input type="date" class="form-control" name="txtfecha_nacimiento" value="{{ $item->fecha_nacimiento }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Municipio de Residencia</label>
                                                    <input type="text" class="form-control" name="txtmunicipio_residencia" value="{{ $item->municipio_residencia }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">País de Nacimiento</label>
                                                    <input type="text" class="form-control" name="txtpais_nacimiento" value="{{ $item->pais_nacimiento }}">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2 mt-4">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Modificar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="text-muted small">Mostrando {{ $datos->count() }} de {{ $datos->total() }} alumnos</div>
            <div>
                @if($datos->lastPage() > 1)
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0 justify-content-end">
                        <li class="page-item {{ $datos->onFirstPage() ? 'disabled' : '' }}">
                            <a href="{{ $datos->previousPageUrl() }}" class="page-link">Anterior</a>
                        </li>

                        @for ($i = 1; $i <= $datos->lastPage(); $i++)
                            <li class="page-item {{ $datos->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $datos->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        <li class="page-item {{ $datos->hasMorePages() ? '' : 'disabled' }}">
                            <a href="{{ $datos->nextPageUrl() }}" class="page-link">Siguiente</a>
                        </li>
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
