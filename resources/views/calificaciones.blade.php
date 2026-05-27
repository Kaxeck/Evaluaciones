@extends('layouts.app')

@section('title', 'Calificaciones')

@section('content')
<section id="calificaciones" class="mb-5">
    <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <p class="text-white-50 mb-2 text-uppercase fw-semibold small">Gestión escolar</p>
                <h1 class="display-6 fw-bold mb-2">Calificaciones</h1>
                <p class="mb-0 text-white-50">Registro de parciales y promedio por alumno y materia.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="btn btn-light btn-lg px-4" data-bs-toggle="modal" data-bs-target="#modalregistar">
                    <i class="fa-solid fa-plus me-2"></i>Añadir Calificación
                </button>
            </div>
        </div>

        <div class="row g-3 mt-4">
            <div class="col-md-6 col-lg-3">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-10 rounded-4 p-3">
                    <div class="text-white-50 small text-uppercase">Registros</div>
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

    <div id="calificacion-alerts"></div>

    <script>
        var res = function() {
            var not = confirm("¿Desea eliminar esta calificación?");
            return not;
        }
    </script>

    <!-- Modal de registro -->
    <div class="modal fade" id="modalregistar" tabindex="-1" aria-labelledby="modalCalificacionCrearLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h1 class="modal-title fs-5" id="modalCalificacionCrearLabel">Añadir calificación</h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('calificaciones.store') }}" method="POST" data-calificacion-form>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Alumno</label>
                                <select class="form-select" name="txtalumno_id">
                                    <option value="">Selecciona un alumno</option>
                                    @foreach($alumnos as $alumno)
                                        <option value="{{ $alumno->id }}">{{ $alumno->nombre }} {{ $alumno->paterno }} {{ $alumno->materno }} - {{ $alumno->centro_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Materia</label>
                                <select class="form-select" name="txtmateria_id">
                                    <option value="">Selecciona una materia</option>
                                    @foreach($materias as $materia)
                                        <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parcial 1</label>
                                <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parcial 2</label>
                                <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial2" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parcial 3</label>
                                <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial3" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Promedio</label>
                                <input type="text" class="form-control promedio-output" value="" readonly>
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
                <h2 class="h5 mb-1 text-primary">Listado de calificaciones</h2>
                <div class="text-muted small">Administración de parciales y promedio.</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Centro de trabajo</th>
                        <th scope="col">Materia</th>
                        <th scope="col">Parcial 1</th>
                        <th scope="col">Parcial 2</th>
                        <th scope="col">Parcial 3</th>
                        <th scope="col">Promedio</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->alumno_nombre }}</td>
                            <td>{{ $item->centro_nombre }}</td>
                            <td>{{ $item->materia_nombre }}</td>
                            <td>{{ $item->parcial1 }}</td>
                            <td>{{ $item->parcial2 }}</td>
                            <td>{{ $item->parcial3 }}</td>
                            <td>{{ $item->promedio }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalditar{{ $item->id }}" class="btn btn-outline-warning btn-sm me-1">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <a href="{{ route('calificaciones.destroy', ['id' => $item->id]) }}" onclick="return res()" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal de modificar datos -->
                        <div class="modal fade" id="modalditar{{ $item->id }}" tabindex="-1" aria-labelledby="modalCalificacionEditarLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white">
                                        <h1 class="modal-title fs-5" id="modalCalificacionEditarLabel{{ $item->id }}">Modificar calificación</h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <form action="{{ route('calificaciones.update') }}" method="POST" data-calificacion-form>
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Código</label>
                                                    <input type="text" class="form-control" name="txtcodigo" value="{{ $item->id }}" readonly>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Alumno</label>
                                                    <select class="form-select" name="txtalumno_id">
                                                        @foreach($alumnos as $alumno)
                                                            <option value="{{ $alumno->id }}" {{ $alumno->id == $item->alumno_id ? 'selected' : '' }}>{{ $alumno->nombre }} {{ $alumno->paterno }} {{ $alumno->materno }} - {{ $alumno->centro_nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Materia</label>
                                                    <select class="form-select" name="txtmateria_id">
                                                        @foreach($materias as $materia)
                                                            <option value="{{ $materia->id }}" {{ $materia->id == $item->materia_id ? 'selected' : '' }}>{{ $materia->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Parcial 1</label>
                                                    <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial1" value="{{ $item->parcial1 }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Parcial 2</label>
                                                    <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial2" value="{{ $item->parcial2 }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Parcial 3</label>
                                                    <input type="number" step="0.01" min="0" class="form-control parcial-input" name="txtparcial3" value="{{ $item->parcial3 }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Promedio</label>
                                                    <input type="text" class="form-control promedio-output" value="{{ $item->promedio }}" readonly>
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

        <div class="card-footer bg-white border-0 py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="text-muted small">Mostrando {{ $datos->count() }} de {{ $datos->total() }} calificaciones</div>
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

    <script>
        (function () {
            const alertsContainer = document.getElementById('calificacion-alerts');

            function showAlert(type, message) {
                if (!alertsContainer) {
                    return;
                }

                alertsContainer.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm mt-3" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }

            function calculateAverage(form) {
                const inputs = Array.from(form.querySelectorAll('.parcial-input'));
                const output = form.querySelector('.promedio-output');
                const values = inputs
                    .map((input) => parseFloat(input.value))
                    .filter((value) => !Number.isNaN(value));

                if (!output) {
                    return;
                }

                if (values.length === 0) {
                    output.value = '';
                    return;
                }

                const average = values.reduce((sum, value) => sum + value, 0) / values.length;
                output.value = average.toFixed(2);
            }

            document.querySelectorAll('form[data-calificacion-form]').forEach((form) => {
                const inputs = form.querySelectorAll('.parcial-input');

                inputs.forEach((input) => {
                    input.addEventListener('input', () => calculateAverage(form));
                });

                calculateAverage(form);

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        },
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        const message = payload.errors
                            ? Object.values(payload.errors).flat()[0]
                            : (payload.message || 'No se pudo guardar la calificación.');
                        showAlert('danger', message);
                        return;
                    }

                    showAlert('success', payload.message || 'Operación realizada correctamente.');
                    setTimeout(() => window.location.reload(), 700);
                });
            });
        })();
    </script>
</section>
@endsection

