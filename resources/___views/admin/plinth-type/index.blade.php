@extends('layouts.app')

@section('title', 'Plinth Type')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Plinth Type</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('plinth-type.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Plinth Type <span style="color:red;">*</span></label>
                                        <input type="text" name="plinth_type" class="form-control"
                                            value="{{ old('plinth_type') }}">
                                        @if ($errors->has('plinth_type'))
                                            <span class="text-danger">{{ $errors->first('plinth_type') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="iStatus" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                        value="{{ request('search') }}" placeholder="Search">
                                    <button class="btn btn-outline-primary">Search</button>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Plinth Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            <tr>
                                                <td><input type="checkbox" class="recordCheckbox"
                                                        value="{{ $row->plinth_type_id }}"></td>
                                                <td>{{ $row->plinth_type }}</td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm toggleStatus {{ $row->iStatus ? 'btn-success' : 'btn-secondary' }}"
                                                        data-id="{{ $row->plinth_type_id }}">
                                                        {{ $row->iStatus ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info editBtn"
                                                        data-id="{{ $row->plinth_type_id }}"
                                                        data-value="{{ $row->plinth_type }}"
                                                        data-status="{{ $row->iStatus }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger deleteBtn"
                                                        data-id="{{ $row->plinth_type_id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editModal">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('plinth-type.update') }}" class="modal-content">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="modal-header">
                                <h5>Edit Plinth Type</h5>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Plinth Type <span style="color:red;">*</span></label>
                                    <input type="text" name="plinth_type" id="edit_value" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <select name="iStatus" id="edit_status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary">Update</button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
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
            $.post("{{ route('plinth-type.status') }}", {
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
            if (!confirm('Are you sure to delete this record?')) return;
            $.post("{{ route('plinth-type.delete') }}", {
                _token: '{{ csrf_token() }}',
                id: $(this).data('id')
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        $('#deleteSelected').on('click', function() {
            const ids = $('.recordCheckbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length == 0) return alert('Select records first');
            if (!confirm('Delete selected records?')) return;

            $.post("{{ route('plinth-type.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids: ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        $(document).on('click', '.editBtn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_value').val($(this).data('value'));
            $('#edit_status').val($(this).data('status'));
            $('#editModal').modal('show');
        });

        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', this.checked);
        });
    </script>
@endsection
