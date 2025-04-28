@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <script>
            Swal.fire('Veiksmīgi!', '{{ session('success') }}', 'success');
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Kļūda!',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire('Kļūda!', '{{ session('error') }}', 'error');
        </script>
    @endif
    <div class="col-md-12">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Lietotāju saraksts</h4>
                <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal"
                    data-action="add">
                    <i class="fa fa-plus"></i>
                    Jauns lietotājs
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">
                                <span class="fw-mediumbold">Jauns</span>
                                <span class="fw-light">lietotājs</span>
                            </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <p class="small">Izveidojiet jaunu lietotāju vai rediģējiet esošu, izmantojot šo formu.</p>
                            <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group form-group-default">
                                            <label>Vārds</label>
                                            <input name="name" type="text" class="form-control"
                                                placeholder="Ievadiet vārdu" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group form-group-default">
                                            <label>E-pasts</label>
                                            <input name="email" type="email" class="form-control"
                                                placeholder="Ievadiet e-pastu" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label>Parole</label>
                                            <input name="password" type="password" class="form-control"
                                                placeholder="Ievadiet paroli">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>Apstiprināt paroli</label>
                                            <input name="password_confirmation" type="password" class="form-control"
                                                placeholder="Apstiprināt paroli">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-6">
                                        <div class="form-group form-group-default">
                                            <label>Primārā valoda</label>
                                            <select name="language" class="form-control">
                                                <option value="lv" {{ old('language') == 'lv' ? 'selected' : '' }}>LV
                                                </option>
                                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>EN
                                                </option>
                                                <option value="ua" {{ old('language') == 'ua' ? 'selected' : '' }}>UA
                                                </option>
                                                <option value="ru" {{ old('language') == 'ru' ? 'selected' : '' }}>RU
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group form-group-default">
                                            <label>Loma</label>
                                            <select name="role" class="form-control">
                                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" form="addUserForm" class="btn btn-primary btn-round">Pievienot</button>
                            <button type="button" class="btn btn-danger btn-round" data-bs-dismiss="modal">Aizvērt</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <div id="add-row_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="add-row_length"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="add-row_filter" class="dataTables_filter"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="add-row" class="display table table-striped table-hover" role="grid"
                                aria-describedby="add-row_info">
                                <thead>
                                    <tr role="row">
                                        <th>Vārds</th>
                                        <th>E-pasts</th>
                                        <th>Loma</th>
                                        <th>Darbība</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Vārds</th>
                                        <th>E-pasts</th>
                                        <th>Loma</th>
                                        <th>Darbība</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr role="row">
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="#" class="btn btn-link btn-primary btn-lg"
                                                        data-bs-toggle="modal" data-bs-target="#addRowModal"
                                                        data-action="edit" data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                        data-role="{{ $user->role }}"
                                                        data-language="{{ $user->language }}" data-bs-toggle="tooltip"
                                                        title="Rediģēt lietotāju">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-danger"
                                                            data-bs-toggle="tooltip" title="Dzēst lietotāju"
                                                            onclick="return confirm('Vai esat pārliecināts?')">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="add-row_info" role="status" aria-live="polite"></div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="add-row_paginate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-row').DataTable({
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 3
                }],
                "order": [
                    [0, 'asc']
                ],
                "language": {
                    "search": "Meklēt:",
                    "lengthMenu": "Rādīt _MENU_ ierakstus",
                    "info": "Rāda _START_ līdz _END_ no _TOTAL_ ierakstiem",
                    "paginate": {
                        "first": "Pirmā",
                        "last": "Pēdējā",
                        "next": "Nākamā",
                        "previous": "Iepriekšējā"
                    }
                }
            });

            $('#addRowModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                if (button.length) {
                    var action = button.data('action');
                    var modal = $(this);
                    if (action === 'edit') {
                        var id = button.data('id');
                        var name = button.data('name');
                        var email = button.data('email');
                        var role = button.data('role');
                        var language = button.data('language');

                        modal.find('form').attr('action', '/admin/users/' + id);
                        modal.find('form').find('input[name="_method"]').remove();
                        modal.find('form').append('<input type="hidden" name="_method" value="PUT">');
                        modal.find('form').append('<input type="hidden" name="id" value="' + id + '">');
                        modal.find('input[name="name"]').val(name);
                        modal.find('input[name="email"]').val(email);
                        modal.find('select[name="role"]').val(role);
                        modal.find('select[name="language"]').val(language);
                        modal.find('input[name="password"]').val('');
                        modal.find('input[name="password_confirmation"]').val('');
                        modal.find('.modal-title').text('Rediģēt lietotāju');
                        modal.find('.btn-primary').text('Saglabāt');
                    } else {
                        modal.find('form').attr('action', '{{ route('admin.users.store') }}');
                        modal.find('form').find('input[name="_method"]').remove();
                        modal.find('form').find('input[name="id"]').remove();
                        modal.find('input[name="name"]').val('');
                        modal.find('input[name="email"]').val('');
                        modal.find('input[name="password"]').val('');
                        modal.find('input[name="password_confirmation"]').val('');
                        modal.find('select[name="role"]').val('user');
                        modal.find('select[name="language"]').val('lv');
                        modal.find('.modal-title').text('Jauns lietotājs');
                        modal.find('.btn-primary').text('Pievienot');
                    }
                }
            });

            @if ($errors->any())
                var oldMethod = '{{ old('_method') }}';
                var oldId = '{{ old('id') }}';
                if (oldMethod === 'PUT' && oldId) {
                    var modal = $('#addRowModal');
                    modal.find('form').attr('action', '/admin/users/' + oldId);
                    modal.find('form').append('<input type="hidden" name="_method" value="PUT">');
                    modal.find('form').append('<input type="hidden" name="id" value="' + oldId + '">');
                    modal.find('.modal-title').text('Rediģēt lietotāju');
                    modal.find('.btn-primary').text('Saglabāt');
                } else {
                    var modal = $('#addRowModal');
                    modal.find('form').attr('action', '{{ route('admin.users.store') }}');
                    modal.find('input[name="_method"]').remove();
                    modal.find('input[name="id"]').remove();
                    modal.find('.modal-title').text('Jauns lietotājs');
                    modal.find('.btn-primary').text('Pievienot');
                }
                $('#addRowModal').modal('show');
            @endif
        });
    </script>
@endsection
