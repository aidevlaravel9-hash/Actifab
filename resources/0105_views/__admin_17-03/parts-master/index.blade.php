@extends('layouts.app')

@section('title', 'Parts Master')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Part</div>
                            <div class="card-body">
                                <form action="{{ route('parts-master.store') }}" method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Parts Category <span style="color:red;">*</span></label>
                                        <select name="parts_category_id" class="form-control">
                                            <option value="">-- Select Category --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->parts_id }}"
                                                    {{ old('parts_category_id') == $cat->parts_id ? 'selected' : '' }}>
                                                    {{ $cat->parts_category_name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('parts_category_id'))
                                            <span class="text-danger">{{ $errors->first('parts_category_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Parts Name <span style="color:red;">*</span></label>
                                        <input type="text" name="parts_name" class="form-control"
                                            value="{{ old('parts_name') }}">
                                        @if ($errors->has('parts_name'))
                                            <span class="text-danger">{{ $errors->first('parts_name') }}</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Part Amount <span style="color:red;">*</span></label>
                                        <input type="text" name="part_amount" class="form-control"
                                            value="{{ old('part_amount') }}">
                                        @if ($errors->has('part_amount'))
                                            <span class="text-danger">{{ $errors->first('part_amount') }}</span>
                                        @endif
                                    </div>
                                    {{-- ✅ IMAGE FIELD (OPTIONAL) --}}
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Part Image <small class="text-muted">(optional)</small>
                                        </label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
                                <form class="d-flex" method="GET" action="{{ route('parts-master.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Parts Name" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary me-2">Search</button>
                                    <a href="{{ route('parts-master.index') }}" class=" btn btn-outline-secondary">
                                        Clear
                                    </a>
                                </form>
                                <button id="deleteSelected" class="btn btn-danger btn-sm">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th width="15%">Action</th>
                                                <th>Category</th>
                                                <th>Part Name</th>
                                                <th>Amount</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr class="text-center">
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->parts_id }}"></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->parts_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            style="width:32px; height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="{{ $row->parts_id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $row->category->parts_category_name ?? '' }}</td>
                                                    <td>{{ $row->parts_name }}</td>
                                                    <td>{{ $row->part_amount }}</td>
                                                    {{-- ✅ IMAGE COLUMN --}}
                                                    <td>
                                                        @if ($row->image)
                                                            <img src="{{ asset('uploads/parts/' . $row->image) }}"
                                                                width="50" height="50"
                                                                style="object-fit:cover;border-radius:4px;">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->parts_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
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


                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('parts-master.update') }}" enctype="multipart/form-data">
                {{-- ✅ REQUIRED --}}
                @csrf

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Part</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Parts Category <span style="color:red;">*</span></label>
                            <select name="parts_category_id" id="edit_parts_category_id" class="form-control">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->parts_id }}">
                                        {{ $cat->parts_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Parts Name <span style="color:red;">*</span></label>
                            <input type="text" name="parts_name" id="edit_parts_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Part Amount <span style="color:red;">*</span></label>
                            <input type="text" name="part_amount" id="edit_part_amount" class="form-control">
                        </div>

                        {{-- ✅ EXISTING IMAGE PREVIEW --}}
                        <div class="mb-3" id="editImagePreview" style="display:none;">
                            <label class="form-label">Current Image</label><br>
                            <img id="editImageTag" src="" width="80" height="80"
                                style="object-fit:cover;border:1px solid #ccc;">
                        </div>

                        {{-- ✅ IMAGE INPUT --}}
                        <div class="mb-3">
                            <label class="form-label">
                                Change Image <small>(optional)</small>
                            </label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            const id = btn.data('id');
            $.post("{{ route('parts-master.status') }}", {
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

            $.get("{{ url('admin/parts-master/edit') }}/" + id, function(data) {

                $('#edit_id').val(data.parts_id);
                $('#edit_parts_category_id').val(data.parts_category_id);
                $('#edit_parts_name').val(data.parts_name);
                $('#edit_part_amount').val(data.part_amount);
                $('#edit_iStatus').val(data.iStatus);

                // ✅ IMAGE PREVIEW
                if (data.image) {
                    $('#editImageTag').attr(
                        'src',
                        "{{ asset('uploads/parts') }}/" + data.image
                    );
                    $('#editImagePreview').show();
                } else {
                    $('#editImagePreview').hide();
                }

                $('#editModal').modal('show');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var deleteModal = document.getElementById('deleteRecordModal');

            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');

                var form = document.getElementById('feeder-delete-form');

                var url = "{{ route('parts-master.destroy', ':id') }}";
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
                        url: "{{ route('parts-master.bulkDelete') }}",
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
