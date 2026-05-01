@extends('layouts.app')

@section('title', 'Busbar Position')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <!-- ADD FORM -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Busbar Position</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('busbar-position.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Busbar Position <span style="color:red;">*</span></label>
                                        <input type="text" name="busbar_position" class="form-control"
                                            value="{{ old('busbar_position') }}">
                                        @if ($errors->has('busbar_position'))
                                            <span class="text-danger">{{ $errors->first('busbar_position') }}</span>
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

                    <!-- LISTING -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" class="form-control me-2"
                                        value="{{ request('search') }}" placeholder="Search Busbar Position">
                                    <button class="btn btn-outline-primary">Search</button>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger">Bulk Delete</button>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Busbar Position</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="recordCheckbox"
                                                        value="{{ $row->busbar_position_id }}">
                                                </td>
                                                <td>{{ $row->busbar_position }}</td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm toggleStatus {{ $row->iStatus ? 'btn-success' : 'btn-secondary' }}"
                                                        data-id="{{ $row->busbar_position_id }}">
                                                        {{ $row->iStatus ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info editBtn"
                                                        data-id="{{ $row->busbar_position_id }}"
                                                        data-value="{{ $row->busbar_position }}"
                                                        data-status="{{ $row->iStatus }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger deleteBtn"
                                                        data-id="{{ $row->busbar_position_id }}">
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

                <!-- EDIT MODAL -->
                <div class="modal fade" id="editModal">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('busbar-position.update') }}" class="modal-content">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="modal-header">
                                <h5>Edit Busbar Position</h5>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Busbar Position <span style="color:red;">*</span></label>
                                    <input type="text" name="busbar_position" id="edit_value" class="form-control">
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
        /* SAFE STATUS TOGGLE */
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            const id = btn.data('id');
            if (!id || !btn.length) return;

            $.post("{{ route('busbar-position.status') }}", {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if (res.status) {
                    if (res.new_status == 1) {
                        btn.removeClass('btn-secondary').addClass('btn-success').text('Active');
                    } else {
                        btn.removeClass('btn-success').addClass('btn-secondary').text('Inactive');
                    }
                }
            });
        });

        /* DELETE */
        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure to delete this record?')) return;
            $.post("{{ route('busbar-position.delete') }}", {
                _token: '{{ csrf_token() }}',
                id: $(this).data('id')
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        /* BULK DELETE */
        $('#deleteSelected').on('click', function() {
            const ids = $('.recordCheckbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (ids.length == 0) return alert('Select records first');
            if (!confirm('Delete selected records?')) return;

            $.post("{{ route('busbar-position.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids: ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        /* EDIT MODAL */
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
