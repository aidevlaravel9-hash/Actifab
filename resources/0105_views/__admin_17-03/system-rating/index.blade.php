@extends('layouts.app')

@section('title', 'System Current Rating')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <!-- Add Form -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Rating</div>
                            <div class="card-body">
                                <form action="{{ route('system-rating.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Rating <span style="color:red;">*</span></label>
                                        <input type="text" name="rating" class="form-control"
                                            value="{{ old('rating') }}">
                                        @if ($errors->has('rating'))
                                            <span class="text-danger">{{ $errors->first('rating') }}</span>
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
                                        value="{{ request('search') }}" placeholder="Search Rating">
                                    <button class="btn btn-outline-primary me-2">Search</button>
                                    <a href="{{ route('system-rating.index') }}" class=" btn btn-outline-secondary">
                                        Clear
                                    </a>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="10%"><input type="checkbox" id="checkAll"></th>
                                                <th width="15%">Action</th>
                                                <th>Rating</th>
                                                <th width="15%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr class="text-center">
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->system_current_rating_id }}"></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">

                                                            <button class="btn btn-sm btn-info editBtn"
                                                                data-id="{{ $row->system_current_rating_id }}"
                                                                data-rating="{{ $row->rating }}"
                                                                data-status="{{ $row->iStatus }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <button
                                                                class="btn btn-sm btn-danger deleteBtn d-flex align-items-center justify-content-center"
                                                                style="width:32px;height:32px;" data-bs-toggle="modal"
                                                                data-bs-target="#deleteRecordModal"
                                                                data-id="{{ $row->system_current_rating_id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>

                                                        </div>
                                                    </td>

                                                    <td>{{ $row->rating }}</td>
                                                    <td>

                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->system_current_rating_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
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
                        <form method="POST" action="{{ route('system-rating.update') }}" class="modal-content">
                            @csrf
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Rating</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Rating <span style="color:red;">*</span></label>
                                    <input type="text" name="rating" id="edit_rating" class="form-control">
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

    <!-- Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>

                        <div class="mt-4 pt-2 fs-15">
                            <h4>Are you sure?</h4>
                            <p class="text-muted mb-0">
                                Are you sure you want to delete this record?
                            </p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">

                        <a href="javascript:void(0);" class="btn btn-danger"
                            onclick="event.preventDefault();document.getElementById('rating-delete-form').submit();">
                            Yes, Delete It!
                        </a>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <form id="rating-delete-form" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Delete Modal End -->



@endsection

@section('scripts')
    <script>
        var deleteModal = document.getElementById('deleteRecordModal');

        deleteModal.addEventListener('show.bs.modal', function(event) {

            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var form = document.getElementById('rating-delete-form');

            form.action = "/admin/system-current-rating/delete/" + id;

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
                        url: "{{ route('system-rating.bulkDelete') }}",
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

    <script>
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this); // cache the clicked button

            $.post("{{ route('system-rating.status') }}", {
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


        {{--  $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure to delete this record?')) return;
            $.post("{{ route('system-rating.delete') }}", {
                _token: '{{ csrf_token() }}',
                id: $(this).data('id')
            }, function(res) {
                if (res.status) location.reload();
            });
        });  --}}


        {{--  $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', this.checked);
        });

        $('#deleteSelected').on('click', function() {
            const ids = $('.recordCheckbox:checked').map(function() {
                return $(this).val();
            }).get();
            if (ids.length == 0) return alert('No records selected.');
            if (!confirm('Delete selected records?')) return;
            $.post("{{ route('system-rating.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });  --}}

        $(document).on('click', '.editBtn', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_rating').val($(this).data('rating'));
            $('#edit_status').val($(this).data('status'));
            $('#editModal').modal('show');
        });
    </script>
@endsection
