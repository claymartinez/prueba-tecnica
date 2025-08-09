<!doctype html>
<html lang="es">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>{{ $empleado ? 'Editar' : 'Crear' }} empleado</title>
  @vite(['resources/css/app.css','resources/js/app.js'])

  <style>
    .form-header {
      background: #e9f2fb;
      border: 1px solid #cfe0f5;
      border-radius: .25rem;
      padding: .75rem 1rem;
    }

    .label-col {
      font-weight: 600;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container py-4">
    <h1 class="mb-3">Crear empleado</h1>

    <div class="form-header mb-4">Los campos con asteriscos (*) son obligatorios</div>

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
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" data-auto-dismiss="8000" data-validation-errors="true">
      <i class="fa fa-exclamation-triangle me-2"></i>Corrige los errores.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <form id="formEmpleado" method="POST" action="{{ $empleado ? route('empleados.update',$empleado) : route('empleados.store') }}" novalidate>
      @csrf
      @if($empleado) @method('PUT') @endif

      <div class="row mb-3">
        <label for="nombre" class="labell col-sm-3 col-form-label text-sm-end label-col">Nombre completo *</label>
        <div class="col-sm-9">
          <input type="text" id="nombre" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            placeholder="Nombre completo del empleado"
            value="{{ old('nombre', $empleado->nombre ?? '') }}">
          @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-3">
        <label for="email" class="labell col-sm-3 col-form-label text-sm-end label-col">Correo electrónico *</label>
        <div class="col-sm-9">
          <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
            placeholder="Correo electrónico"
            value="{{ old('email', $empleado->email ?? '') }}">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-3">
        <span class="labell col-sm-3 col-form-label text-sm-end label-col">Sexo *</span>
        <div class="col-sm-9">
          <div class="form-check">
            <input class="form-check-input @error('sexo') is-invalid @enderror" type="radio" name="sexo" id="sexoM" value="M"
              @checked(old('sexo', $empleado->sexo ?? '')==='M')>
            <label class="form-check-label" for="sexoM">Masculino</label>
          </div>
          <div class="form-check">
            <input class="form-check-input @error('sexo') is-invalid @enderror" type="radio" name="sexo" id="sexoF" value="F"
              @checked(old('sexo', $empleado->sexo ?? '')==='F')>
            <label class="form-check-label" for="sexoF">Femenino</label>
          </div>
          @error('sexo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-3">
        <label for="area_id" class="labell col-sm-3 col-form-label text-sm-end label-col">Área *</label>
        <div class="col-sm-9">
          <select id="area_id" name="area_id" class="form-select @error('area_id') is-invalid @enderror">
            <option value="">Seleccione</option>
            @foreach($areas as $a)
            <option value="{{ $a->id }}" @selected(old('area_id', $empleado->area_id ?? '')==$a->id)>{{ $a->nombre }}</option>
            @endforeach
          </select>
          @error('area_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-3">
        <label for="descripcion" class="labell col-sm-3 col-form-label text-sm-end label-col">Descripción *</label>
        <div class="col-sm-9">
          <textarea id="descripcion" name="descripcion" rows="3" class="form-control @error('descripcion') is-invalid @enderror"
            placeholder="Descripción de la experiencia del empleado">{{ old('descripcion', $empleado->descripcion ?? '') }}</textarea>
          @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="boletin" name="boletin" value="1"
              @checked(old('boletin', $empleado->boletin ?? false))>
            <label class="form-check-label" for="boletin">Deseo recibir boletín informativo</label>
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <span class="labell col-sm-3 col-form-label text-sm-end label-col">Roles *</span>
        <div class="col-sm-9" id="rolesGroup">
          @foreach($roles as $r)
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" id="rol{{ $r->id }}" value="{{ $r->id }}"
              @checked( in_array($r->id, old('roles', $empleado ? $empleado->roles->pluck('id')->all() : [])) )>
            <label class="form-check-label" for="rol{{ $r->id }}">{{ $r->nombre }}</label>
          </div>
          @endforeach
          @error('roles')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a class="btn btn-secondary" href="{{ route('empleados.index') }}">Volver</a>
        </div>
      </div>
    </form>
  </div>
</body>

</html>