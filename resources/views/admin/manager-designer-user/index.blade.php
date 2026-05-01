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
                                        <label>First Name <span style="color:red;">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                            placeholder="Enter first name" value="{{ old('first_name') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email <span style="color:red;">*</span></label>
                                        <input type="email" name="email" placeholder="Enter Email" class="form-control"
                                            value="{{ old('email') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Mobile <span style="color:red;">*</span></label>
                                        <input type="text" name="mobile_number" placeholder="Enter Mobile number"
                                            class="form-control" value="{{ old('mobile_number') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Role <span style="color:red;">*</span></label>
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

                                    <button class="btn btn-primary w-10">Submit</button>

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

                                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="{{ $row->manager_designer_user_id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>

                                                        <button class="btn btn-warning btn-sm passwordBtn"
                                                            data-id="{{ $row->manager_designer_user_id }}"
                                                            data-name="{{ $row->first_name }}" title="Change Password">
                                                            <i class="fas fa-key"></i>
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
                            <label>Email</label>
                            <input type="email" id="edit_email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Mobile</label>
                            <input type="text" id="edit_mobile_number" name="mobile_number" class="form-control">
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

    <!-- DELETE MODAL -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                    </lord-icon>

                    <h4 class="mt-3">Are you sure?</h4>
                    <p class="text-muted">Are you sure you want to delete this user?</p>

                    <div class="d-flex justify-content-center gap-2 mt-4">

                        <a href="javascript:void(0);" class="btn btn-danger"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                    </div>

                    <form id="user-delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>

            </div>
        </div>
    </div>

    {{-- CHANGE PASSWORD MODAL --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('manager-designer-user.password-update') }}">
                @csrf
                <input type="hidden" name="user_id" id="password_user_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3">Update password for <strong id="password_user_name"></strong>.</p>

                        <div class="mb-3">
                            <label>New Password</label>
                            <div class="input-group">
                                <input type="password" placeholder="Enter New Password" name="password"
                                    id="new_password_input" class="form-control" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary togglePasswordBtn"
                                    data-target="#new_password_input">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" placeholder="Enter Confirm Password"
                                    id="confirm_password_input" class="form-control" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary togglePasswordBtn"
                                    data-target="#confirm_password_input">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Change Password</button>
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var deleteModal = document.getElementById('deleteRecordModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');

                var form = document.getElementById('user-delete-form');

                var url = "{{ route('manager-designer-user.destroy', ':id') }}";
                url = url.replace(':id', id);

                form.action = url;

            });

        });
    </script>
    <script>
        $(document).on('click', '.passwordBtn', function() {
            const userId = $(this).data('id');
            const userName = $(this).data('name');

            $('#password_user_id').val(userId);
            $('#password_user_name').text(userName);

            new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
        });

        $(document).on('click', '.togglePasswordBtn', function() {
            const targetSelector = $(this).data('target');
            const input = $(targetSelector);
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    </script>

@endsection
