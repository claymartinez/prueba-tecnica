<!doctype html>
<html lang="es">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Lista de empleados</title>
  @vite(['resources/css/app.css','resources/js/app.js'])

</head>
<style>
  .table-light th {
    min-width: 100px;
    white-space: nowrap;
  }
</style>

<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><i class="fa-solid fa-users me-2"></i>Lista de empleados</h1>
      <a class="btn btn-primary" href="{{ route('empleados.create') }}"><i class="fa-solid fa-user-plus me-1"></i> Crear</a>
    </div>

    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" data-auto-dismiss="5000">
      <i class="fa-regular fa-circle-check me-2"></i>{{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" data-auto-dismiss="7000">
      <i class="fa-regular fa-circle-xmark me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th><i class="fa-solid fa-user me-1"></i> Nombre</th>
            <th><i class="fa-solid fa-at me-1"></i> Email</th>
            <th><i class="fa-solid fa-venus-mars "></i> Sexo</th>
            <th><i class="fa-solid fa-briefcase me-1"></i> Área</th>
            <th><i class="fa-solid fa-envelope me-1"></i> Boletín</th>
            <th><i class="fa-solid fa-id-badge me-1"></i> Roles</th>
            <th> Modificar</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          @forelse($empleados as $e)
          <tr>
            <td>{{ $e->nombre }}</td>
            <td>{{ $e->email }}</td>
            <td>{{ $e->sexo === 'M' ? 'Masculino' : 'Femenino' }}</td>
            <td>{{ $e->area?->nombre }}</td>
            <td>{{ $e->boletin ? 'Sí' : 'No' }}</td>
            <td>
              @foreach($e->roles as $r)
              <span class="badge bg-secondary">{{ $r->nombre }}</span>
              @endforeach
            </td>
            <td>
              <a class="btn btn-sm " href="{{ route('empleados.edit',$e) }}" title="Modificar">
                <i class="fa-regular fa-pen-to-square fa-2x"></i>
              </a>
            </td>
            <td>
              <form class="d-inline" action="{{ route('empleados.destroy',$e) }}" method="POST" onsubmit="return confirm('¿Eliminar empleado?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm " title="Eliminar">
                  <i class="fa-regular fa-trash-can fa-2x"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted">Sin registros</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($empleados, 'links'))
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="text-muted small">
        Mostrando {{ $empleados->firstItem() ?? 0 }}-{{ $empleados->lastItem() ?? 0 }} de {{ $empleados->total() ?? count($empleados) }}
      </div>
      <div>
        {{ $empleados->links('pagination::bootstrap-5') }}
      </div>
    </div>
    @endif
  </div>
</body>

</html>