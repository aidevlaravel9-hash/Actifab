@extends('layouts.app')

@section('title', 'Manager / Designer Users')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="row">

                    {{-- ADD FORM --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add User</div>
                            <div class="card-body">

                                <form action="{{ route('manager-designer-user.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label>First Name *</label>
                                        <input type="text" name="first_name" class="form-control"
                                            value="{{ old('first_name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email *</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Mobile *</label>
                                        <input type="text" name="mobile_number" class="form-control"
                                            value="{{ old('mobile_number') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Role *</label>
                                        <select name="role_type" class="form-control">
                                            <option value="manager">Manager</option>
                                            <option value="designer">Designer</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                    <button class="btn btn-primary w-100">Submit</button>

                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- LIST --}}
                    <div class="col-md-8">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between">

                                <form method="GET" class="d-flex">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control me-2" placeholder="Search user">
                                    <button class="btn btn-outline-primary">Search</button>
                                </form>

                            </div>

                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                                                    <td>{{ $row->email }}</td>
                                                    <td>{{ $row->mobile_number }}</td>
                                                    <td>{{ ucfirst($row->role_type) }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $row->status ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $row->status ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>

                                                        <button class="btn btn-info btn-sm editBtn"
                                                            data-id="{{ $row->manager_designer_user_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-danger btn-sm deleteBtn"
                                                            data-id="{{ $row->manager_designer_user_id }}">
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

                </div>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('manager-designer-user.update') }}">
                @csrf

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Edit User</h5>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>First Name</label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Last Name</label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Mobile</label>
                            <input type="text" id="edit_mobile_number" name="mobile_number" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Password (optional)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Role</label>
                            <select id="edit_role_type" name="role_type" class="form-control">
                                <option value="manager">Manager</option>
                                <option value="designer">Designer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select id="edit_status" name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')

    <script>
        $(document).on('click', '.editBtn', function() {

            let id = $(this).data('id');

            $.get("{{ url('admin/manager-designer-user/edit') }}/" + id, function(data) {

                $('#edit_id').val(data.manager_designer_user_id);
                $('#edit_first_name').val(data.first_name);
                $('#edit_last_name').val(data.last_name);
                $('#edit_email').val(data.email);
                $('#edit_mobile_number').val(data.mobile_number);
                $('#edit_role_type').val(data.role_type);
                $('#edit_status').val(data.status);

                new bootstrap.Modal(document.getElementById('editModal')).show();

            });

        });


        $(document).on('click', '.deleteBtn', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post("{{ route('manager-designer-user.destroy') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id
                    }, function(res) {

                        if (res.status) {
                            location.reload();
                        }

                    });

                }

            });

        });
    </script>

@endsection
