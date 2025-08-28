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
                <h4 class="card-title">Lietotāju progress</h4>
            </div>
        </div>
        <div class="card-body">


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
                                        <th>Progress</th>
                                        <th>Sertifikāts</th>
                                        <th>Darbība</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Vārds</th>
                                        <th>Progress</th>
                                        <th>Sertifikāts</th>
                                        <th>Darbība</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    {{-- @dd($items) --}}
                                    @foreach ($items as $item)
                                        <tr role="row">
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <div class="progress w-75" style="height: 25px;">
                                                    <div class="progress-bar progress-bar-striped bg-success"
                                                        role="progressbar" style="width: {{ $item->percentage }}%"
                                                        aria-valuenow="{{ $item->percentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ round($item->percentage) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($item->certificate_date)
                                                    <a href="{{ route('certificate.download', [$item->user_id, $item->course_id]) }}"
                                                        class="btn btn-label-info btn-round me-2">
                                                        Lejupielādēt <i class="fas fa-file-download"></i>
                                                    </a>
                                                @else
                                                    <p class="text-center">Nav</p>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="{{ route('user.progress', parameters: [$item->user_id]) }}"
                                                        class="btn btn-label-info btn-round me-2">Progress visos moduļos
                                                        <i class="fas fa-angle-right"></i></a>
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

        });
    </script>
@endsection
