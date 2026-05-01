@extends('layouts.app')

@section('title', 'Lock System')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <!-- Add Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Lock System</div>
                            <div class="card-body">
                                <form action="{{ route('lock-system.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Lock System <span style="color:red;">*</span></label>
                                        <input type="text" name="lock_system" class="form-control"
                                            value="{{ old('lock_system') }}">
                                        @if ($errors->has('lock_system'))
                                            <span class="text-danger">{{ $errors->first('lock_system') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
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

                    <!-- Listing -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                        value="{{ request('search') }}" placeholder="Search Lock System">
                                    <button class="btn btn-outline-primary">Search</button>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Lock System</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->lock_system_id }}"></td>
                                                    <td>{{ $row->lock_system }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->lock_system_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->lock_system_id }}"
                                                            data-value="{{ $row->lock_system }}"
                                                            data-status="{{ $row->iStatus }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->lock_system_id }}">
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

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('lock-system.update') }}" class="modal-content">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Lock System</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Lock System <span style="color:red;">*</span></label>
                                    <input type="text" name="lock_system" id="edit_value" class="form-control">
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
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            const id = btn.data('id');
            if (!id || btn.length === 0) return;

            $.post("{{ route('lock-system.status') }}", {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if (res.status && btn.length) {
                    if (res.new_status == 1) {
                        btn.removeClass('btn-secondary').addClass('btn-success').text('Active');
                    } else {
                        btn.removeClass('btn-success').addClass('btn-secondary').text('Inactive');
                    }
                }
            });
        });

        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure to delete this record?')) return;
            $.post("{{ route('lock-system.delete') }}", {
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
            if (ids.length == 0) return alert('No records selected.');
            if (!confirm('Delete selected records?')) return;
            $.post("{{ route('lock-system.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids
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
    </script>
@endsection
