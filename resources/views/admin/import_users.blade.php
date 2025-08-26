@extends('layouts.admin')

@section('content')
    <div class="col-md-12">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">{{ $title }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.import.users.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="excel_file">Excel Fails (kolonnas: Vārds, E-pasts, Valoda)</label>
                        <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required
                            class="form-control my-2">
                    </div>
                    <button type="submit" class="btn btn-primary btn-round ms-auto">
                        Importēt
                    </button>
                </form>
            </div>
        </div>
    @endsection
