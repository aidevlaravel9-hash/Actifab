@extends('layouts.app')

@section('title', 'Feeder Type')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Feeder Type</div>
                            <div class="card-body">
                                <form action="{{ route('feeder-type.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                                        <input type="text" name="feeder_type" class="form-control"
                                            value="{{ old('feeder_type') }}">
                                        @if ($errors->has('feeder_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('feeder_type') }}
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

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <form class="d-flex" method="GET" action="{{ route('feeder-type.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Feeder Type" value="{{ request('search') }}">
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
                                                <th>Feeder Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->feeder_type_id }}"></td>
                                                    <td>{{ $row->feeder_type }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->feeder_type_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->feeder_type_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->feeder_type_id }}">
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

                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="POST" action="{{ route('feeder-type.update') }}">
                                @csrf
                                <input type="hidden" name="edit_id" id="edit_id">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Feeder Type</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                                            <input type="text" name="feeder_type" id="edit_feeder_type"
                                                class="form-control">
                                            @if ($errors->has('feeder_type'))
                                                <span class="text-danger">
                                                    {{ $errors->first('feeder_type') }}
                                                </span>
                                            @endif
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
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
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
            const id = btn.data('id');

            if (!id) return;

            $.ajax({
                url: "{{ route('feeder-type.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    if (res.status) {
                        const isActive = res.new_status == 1;

                        btn
                            .removeClass('btn-success btn-secondary')
                            .addClass(isActive ? 'btn-success' : 'btn-secondary')
                            .text(isActive ? 'Active' : 'Inactive');
                    }
                },
                error: function() {
                    alert('Status update failed. Try again.');
                }
            });
        });
    </script>


    <script>
        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#deleteSelected').on('click', function() {
            const selected = $('.recordCheckbox:checked');
            if (selected.length === 0) {
                alert('Please select at least one record to delete.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected records?')) {
                return;
            }

            const ids = [];
            selected.each(function() {
                ids.push($(this).val());
            });

            $.ajax({
                url: "{{ route('feeder-type.bulkDelete') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(res) {
                    if (res.status) {
                        location.reload();
                    }
                }
            });
        });
    </script>

    <script>
        $('.editBtn').on('click', function() {
            const id = $(this).data('id');

            $.get("{{ url('admin/feeder-type/edit') }}/" + id, function(data) {
                $('#edit_id').val(data.feeder_type_id);
                $('#edit_feeder_type').val(data.feeder_type);
                $('#edit_iStatus').val(data.iStatus);
                $('#editModal').modal('show');
            });
        });
    </script>
    <script>
        $('.deleteBtn').on('click', function() {
            if (!confirm('Are you sure you want to delete this record?')) return;

            const id = $(this).data('id');

            $.ajax({
                url: "{{ route('feeder-type.destroy') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(res) {
                    if (res.status) {
                        location.reload();
                    }
                }
            });
        });
    </script>
@endsection
