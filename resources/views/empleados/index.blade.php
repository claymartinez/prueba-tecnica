<!doctype html>
<html lang="es">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>Lista de empleados</title>
  <!-- Preload CSS principal para reducir FOUC -->
  <link rel="preload" href="{{ vite_asset('resources/js/app.js') }}" as="script">
  @vite(['resources/css/app.css'])
  @vite(['resources/js/app.js'])
  <style>
    /* CSS crítico mínimo */
    body { background:#f8f9fa; }
    .table-light th { min-width:100px; white-space:nowrap; }
  </style>
</head>

<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><i class="fa-solid fa-users me-2"></i>Lista de empleados</h1>
      <a class="btn btn-primary" href="{{ route('empleados.create') }}"><i class="fa-solid fa-plus me-1"></i> Crear</a>
    </div>

    @if (session('status'))
    <div class="alert alert-success"><i class="fa-regular fa-circle-check me-2"></i>{{ session('status') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger"><i class="fa-regular fa-circle-xmark me-2"></i>{{ session('error') }}</div>
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
  </div>
</body>

</html>