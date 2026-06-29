{{-- ============================================================
     ARCHIVO: resources/views/admin/edit-category.blade.php
     Formulario para editar una categoría existente
     CUMPLE: RF-16 (modificar categoría)
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Categoría')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Administracion</a></li>
            <li class="breadcrumb-item"><a href="/admin/categories" class="text-decoration-none">Categorias</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>

    <h1 class="section-title">Editar Categoría</h1>
    <hr class="section-title-underline">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-box">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="/admin/categories/{{ $category->id }}"
                      class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $category->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">El nombre es obligatorio.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label optional">Descripción</label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-control">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-floppy-disk"></i>Actualizar
                        </button>
                        <a href="/admin/categories" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
