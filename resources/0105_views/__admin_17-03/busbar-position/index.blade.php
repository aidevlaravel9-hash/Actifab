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
                                    <button class="btn btn-outline-primary me-2">Search</button>
                                    <a href="{{ route('busbar-position.index') }}" class=" btn btn-outline-secondary">
                                        Clear
                                    </a>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger">Bulk Delete</button>
                            </div>

                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th width="15%">Action</th>
                                            <th>Busbar Position</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $row)
                                            <tr class="text-center">
                                                <td>
                                                    <input type="checkbox" class="recordCheckbox"
                                                        value="{{ $row->busbar_position_id }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-info editBtn"
                                                        data-id="{{ $row->busbar_position_id }}"
                                                        data-value="{{ $row->busbar_position }}"
                                                        data-status="{{ $row->iStatus }}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-danger deleteBtn"
                                                        style="width:32px; height:32px;" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal"
                                                        data-id="{{ $row->busbar_position_id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                                <td>{{ $row->busbar_position }}</td>
                                                <td>
                                                    <button
                                                        class="btn btn-sm toggleStatus {{ $row->iStatus ? 'btn-success' : 'btn-secondary' }}"
                                                        data-id="{{ $row->busbar_position_id }}">
                                                        {{ $row->iStatus ? 'Active' : 'Inactive' }}
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>

                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">
                                Are you sure you want to remove this Project ?
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">

                        <a href="javascript:void(0);" class="btn btn-danger mx-2"
                            onclick="event.preventDefault(); document.getElementById('feeder-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">
                            Close
                        </button>

                        <form id="feeder-delete-form" method="POST">
                            @csrf
                            @method('DELETE')
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var deleteModal = document.getElementById('deleteRecordModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');

                var form = document.getElementById('feeder-delete-form');

                var url = "{{ route('busbar-position.delete', ':id') }}";
                url = url.replace(':id', id);

                form.action = url;
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
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one record to delete.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            const ids = [];
            selected.each(function() {
                ids.push($(this).val());
            });

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('busbar-position.bulkDelete') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(res) {

                            if (res.status) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Selected records have been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong.',
                                'error'
                            );
                        }
                    });

                }

            });

        });
    </script>
@endsection
