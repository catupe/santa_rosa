<div class="alert alert-secondary alert-dismissible fade show" role="alert">
  @if(count($mensajes))
      <ul class="list-unstyled">
        @foreach ($mensajes as $k => $mensaje)
          <li>{{ $mensaje }}</li>
        @endforeach
      </ul>
  @endif
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
