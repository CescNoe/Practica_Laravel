@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <h1>Lista de Marcas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" id="btnRegistrar">Agregar marca</button>
            <div class="card-body">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha Creación</th>
                            <th>Fecha Actualización</th>
                            <th width="20px"></th>
                            <th width="20px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>
                                    <img src="{{ asset($brand->logo == '' ? asset('storage/brand_logo/no-image-icon-6.png') : $brand->logo) }}"
                                    width="70px" height="50px" alt="{{ $brand->name }}">
                                </td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->description }}</td>
                                <td>{{ $brand->created_at }}</td>
                                <td>{{ $brand->updated_at }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm btnEditar" id="{{ $brand->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                        class="frmDelete">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Formulario de Marcas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Contenido del modal se carga dinámicamente -->
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).on('click', '.frmDelete', function(e) {
        e.preventDefault();
        var form = $(this);
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        refreshTable();
                        Swal.fire({
                            title: '¡Eliminada!',
                            text: 'La marca ha sido eliminada.',
                            icon: 'success',
                            draggable: true
                        });
                    },
                    error: function(response) {
                        var error = response.responseJSON;
                        Swal.fire({
                            title: 'Error',
                            text: error.message,
                            icon: 'error',
                            draggable: true
                        });
                    }
                });
            }
        })
    });

    $('#btnRegistrar').click(function() {
            $.ajax({
                url: "{{ route('admin.brands.create') }}",
                type: "GET",
                success: function(response) {
                    $('#modal .modal-body').html(response);
                    $('#modal .modal-title').html("Nueva marca");
                    $('#modal').modal('show');
                    $('#modal form').on('submit', function(e) {
                        e.preventDefault();

                        var form = $(this);
                        var formData = new FormData(this);

                        $.ajax({
                            "url": form.attr('action'),
                            "type": form.attr('method'),
                            "data": formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#modal').modal('hide');
                                refreshTable();
                                Swal.fire({
                                    title: "Proceso exitoso!",
                                    text: response.message,
                                    icon: "success",
                                    draggable: true
                                });
                            },
                            error: function(response) {
                                var error = response.responseJSON;
                                Swal.fire({
                                    title: "Error!",
                                    text: error.message,
                                    icon: "error",
                                    draggable: true
                                });
                            }
                        });
                    });
                }
            });
        });

        $(document).on('click', '.btnEditar', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('admin.brands.edit', 'id') }}".replace('id', id),
                type: "GET",
                success: function(response) {
                    $('#modal .modal-body').html(response);
                    $('#modal .modal-title').html("Editar marca");
                    $('#modal').modal('show');
                    $('#modal form').on('submit', function(e) {
                        e.preventDefault();

                        var form = $(this);
                        var formData = new FormData(this);

                        $.ajax({
                            "url": form.attr('action'),
                            "type": form.attr('method'),
                            "data": formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#modal').modal('hide');
                                refreshTable();
                                Swal.fire({
                                    title: "Proceso exitoso!",
                                    text: response.message,
                                    icon: "success",
                                    draggable: true
                                });
                            },
                            error: function(response) {
                                var error = response.responseJSON;
                                Swal.fire({
                                    title: "Error!",
                                    text: error.message,
                                    icon: "error",
                                    draggable: true
                                });
                            }
                        });
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#table').DataTable({
                "ajax": "{{ route('admin.brands.index') }}",
                "columns": [{
                    "data": "logo",
                    "orderable": false,
                    "searchable": false,
                }, {
                    "data": "name",
                }, {
                    "data": "description",
                }, {
                    "data": "created_at",
                }, {
                    "data": "updated_at",
                }, {
                    "data": "edit",
                    "orderable": false,
                    "searchable": false,
                }, {
                    "data": "delete",
                    "orderable": false,
                    "searchable": false,
                }],
                "language": {
                    "url": 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        function refreshTable() {
            var table = $("#table").DataTable();
            table.ajax.reload(null, false)
        }
</script>
@stop
