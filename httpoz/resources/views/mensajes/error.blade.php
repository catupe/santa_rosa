<div class="alert alert-danger alert-dismissible fade show" role="alert">
  @if( isset($mensaje) and ($mensaje != ""))
    {{ $mensaje }}
  @endif
  @if(count($errors))
      <ul class="list-unstyled">
        @foreach ($errors as $k => $error)
          <li>{{ $error }}</li>
          @endforeach
      </ul>
  @endif
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
