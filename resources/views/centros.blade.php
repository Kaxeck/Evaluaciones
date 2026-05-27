@extends('layouts.app')

@section('title', 'Centros')

@section('content')
<section id="centros" class="mb-5">
    <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <p class="text-white-50 mb-2 text-uppercase fw-semibold small">Gestión escolar</p>
                <h1 class="display-6 fw-bold mb-2">Centros Educativos</h1>
                <p class="mb-0 text-white-50">Administración de centros educativos.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div>
                    <button class="btn btn-light btn-lg px-4" data-bs-toggle="modal" data-bs-target="#modalregistar">
                        <i class="fa-solid fa-building me-2"></i>Añadir Centro
                    </button>
                </div>
                <div class="mt-2">
                    <button class="btn btn-outline-light btn-sm px-4" data-bs-toggle="modal" data-bs-target="#modalimportar">
                        <i class="fa-solid fa-file-import me-2"></i>Importar
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 mt-4">
            <div class="col-md-6 col-lg-3">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3">
                    <div class="text-white-50 small text-uppercase">Centros</div>
                    <div class="fs-4 fw-bold">{{ $datos->total() }}</div>
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

    <!-- Modal para importar centros -->
    <div class="modal fade" id="modalimportar" tabindex="-1" aria-labelledby="modalimportarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="modalimportarLabel">Importar centros</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('centros.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Seleccionar archivo Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        </div>
                        <div class="text-muted small mb-3">Formato esperado en Excel: Clave, Telebachillerato, Clave del Centro de Trabajo, Municipio, Encargado, Correo del encargado. Si incluyes un ID, se ignorará porque SQL lo autogenera.</div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Importar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var res = function() {
            return confirm("¿Desea eliminar este centro?");
        }

    </script>

    <!-- Modal para agregar nuevo centro -->
    <div class="modal fade" id="modalregistar" tabindex="-1" aria-labelledby="modalCentroCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="modalCentroCrearLabel">Añadir datos del centro</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('centros.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Clave</label>
                                <input type="text" class="form-control" name="txtclave">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="txtnombre">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Clave CCT</label>
                                <input type="text" class="form-control" name="txtclave_cct">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Municipio</label>
                                <input type="text" class="form-control" name="txtmunicipio">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Encargado</label>
                                <input type="text" class="form-control" name="txtencargado">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Correo del encargado</label>
                                <input type="email" class="form-control" name="txtcorreo_encargado">
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

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="width:95%; margin:0 auto;">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
            <div>
                <h2 class="h5 mb-1 text-primary">Listado de centros</h2>
                <div class="text-muted small">Administración de registros con acciones de edición y eliminación.</div>
            </div>
        </div>

        <!-- Tabla de datos -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Clave</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Clave CCT</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Encargado</th>
                        <th scope="col">Correo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody table-group-divider>
                    @foreach($datos as $item)
                    <tr>
                        <th scope="row">{{ $item->id }}</th>
                        <td>{{ $item->clave }}</td>
                        <td>{{ $item->nombre }}</td>
                        <td>{{ $item->clave_cct }}</td>
                        <td>{{ $item->municipio }}</td>
                        <td>{{ $item->encargado }}</td>
                        <td>{{ $item->correo_encargado }}</td>
                        <td class="text-end text-nowrap">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalditar{{ $item->id }}" class="btn btn-outline-warning btn-sm me-1">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                                <a href="{{ route('centros.destroy', ['id' => $item->id]) }}" onclick="return res()" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Modal para editar centro -->
                    <div class="modal fade" id="modalditar{{ $item->id }}" tabindex="-1" aria-labelledby="modalCentroEditarLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-primary text-white">
                                    <h1 class="modal-title fs-5" id="modalCentroEditarLabel{{ $item->id }}">Modificar datos del centro</h1>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                        <form action="{{ route('centros.update') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Código</label>
                                                <input type="text" class="form-control" name="txtcodigo" value="{{ $item->id }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Clave</label>
                                                <input type="text" class="form-control" name="txtclave" value="{{ $item->clave }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="txtnombre" value="{{ $item->nombre }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Clave CCT</label>
                                                <input type="text" class="form-control" name="txtclave_cct" value="{{ $item->clave_cct }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Municipio</label>
                                                <input type="text" class="form-control" name="txtmunicipio" value="{{ $item->municipio }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Encargado</label>
                                                <input type="text" class="form-control" name="txtencargado" value="{{ $item->encargado }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Correo del encargado</label>
                                                <input type="email" class="form-control" name="txtcorreo_encargado" value="{{ $item->correo_encargado }}">
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
            <div class="text-muted small">Mostrando {{ $datos->count() }} de {{ $datos->total() }} centros</div>
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
