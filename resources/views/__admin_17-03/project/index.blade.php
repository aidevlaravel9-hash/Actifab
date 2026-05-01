@extends('layouts.app')

@section('title', 'Project Listing')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <form method="GET" action="{{ route('project.index') }}" class="d-flex">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search Project Name" class="form-control me-2">
                            <button class="btn btn-outline-primary">Search</button>
                        </form>
                        <a href="{{ route('project.create') }}" class="btn btn-success">Add New</a>
                    </div>
                    <div class="card-body">
                        <button id="deleteSelected" class="btn btn-danger mb-2">Bulk Delete</button>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Project Name</th>
                                        <th>Customer Name</th>
                                        <th>Contact Person</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                        <tr>
                                            <td><input type="checkbox" class="recordCheckbox"
                                                    value="{{ $row->project_id }}"></td>
                                            <td>{{ $row->project_name }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ $row->contact_person }}</td>
                                            <td>
                                                <button
                                                    class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                    data-id="{{ $row->project_id }}">
                                                    {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('project.edit', $row->project_id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger deleteBtn"
                                                    data-id="{{ $row->project_id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            $.post("{{ route('project.status') }}", {
                _token: '{{ csrf_token() }}',
                id: btn.data('id')
            }, function(res) {
                if (res.status) {
                    btn.removeClass('btn-success btn-secondary')
                        .addClass(res.new_status == 1 ? 'btn-success' : 'btn-secondary')
                        .text(res.new_status == 1 ? 'Active' : 'Inactive');
                }
            });
        });

        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure you want to delete this project?')) return;
            $.post("{{ route('project.destroy') }}", {
                _token: '{{ csrf_token() }}',
                id: $(this).data('id')
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', this.checked);
        });

        $('#deleteSelected').on('click', function() {
            const ids = $('.recordCheckbox:checked').map(function() {
                return $(this).val();
            }).get();
            if (ids.length === 0) return alert('Please select records to delete.');
            if (!confirm('Are you sure to delete selected projects?')) return;

            $.post("{{ route('project.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids: ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });
    </script>

@endsection
