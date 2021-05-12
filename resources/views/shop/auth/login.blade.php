@extends('shop.layouts.app')

@section('content')
    <div class="page page-center">
        <div class="container-tight py-4">
            <form class="card card-md" method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <div class="card-body">
                    <h1 class="text-center mb-4">TT Import App</h1>
                    <div class="mb-3">
                        <label class="form-label" for="name">Usuario</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" placeholder="Nombre de usuario">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Clave</label>
                        <div class="input-group input-group-flat">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   autocomplete="current-password">
                            <span class="input-group-text">
                  <a href="#" class="link-secondary" title="" data-bs-toggle="tooltip"
                     data-bs-original-title="Show password"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle
                            cx="12" cy="12" r="2"></circle><path
                            d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path></svg>
                  </a>
                </span>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="form-check-label">Recordarme</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </div>
                </div>


            </form>

        </div>
    </div>
@endsection
