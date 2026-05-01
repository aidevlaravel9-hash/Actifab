@extends('layouts.app')

@section('title', 'Panel Type')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    {{-- Add Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Panel Type</div>
                            <div class="card-body">
                                <form action="{{ route('panel-type.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Panel Type <span style="color:red;">*</span></label>
                                        <input type="text" name="panel_type" class="form-control"
                                            value="{{ old('panel_type') }}">
                                        @if ($errors->has('panel_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('panel_type') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="iStatus" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Listing --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <form class="d-flex" method="GET" action="{{ route('panel-type.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Panel Type" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary">Search</button>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger btn-sm">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Panel Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->panel_type_id }}"></td>
                                                    <td>{{ $row->panel_type }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->panel_type_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->panel_type_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->panel_type_id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $data->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Scripts Inline --}}

                </div>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ route('panel-type.update') }}">
                    @csrf
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Panel Type</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Panel Type <span style="color:red;">*</span></label>
                                <input type="text" name="panel_type" id="edit_panel_type" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="iStatus" id="edit_iStatus" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')

    <script>
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            const id = btn.data('id');
            $.post("{{ route('panel-type.status') }}", {
                _token: "{{ csrf_token() }}",
                id: id
            }, function(res) {
                if (res.status) {
                    btn.removeClass('btn-success btn-secondary')
                        .addClass(res.new_status == 1 ? 'btn-success' : 'btn-secondary')
                        .text(res.new_status == 1 ? 'Active' : 'Inactive');
                }
            });
        });

        $(document).on('click', '.editBtn', function() {
            const id = $(this).data('id');
            $.get("{{ url('admin/panel-type/edit') }}/" + id, function(data) {
                $('#edit_id').val(data.panel_type_id);
                $('#edit_panel_type').val(data.panel_type);
                $('#edit_iStatus').val(data.iStatus);
                $('#editModal').modal('show');
            });
        });

        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure you want to delete this record?')) return;
            const id = $(this).data('id');
            $.post("{{ route('panel-type.destroy') }}", {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#deleteSelected').on('click', function() {
            const selected = $('.recordCheckbox:checked');
            if (selected.length === 0) return alert('Select at least one record.');
            if (!confirm('Are you sure to delete selected records?')) return;
            const ids = selected.map(function() {
                return $(this).val();
            }).get();
            $.post("{{ route('panel-type.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids: ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });
    </script>

@endsection
