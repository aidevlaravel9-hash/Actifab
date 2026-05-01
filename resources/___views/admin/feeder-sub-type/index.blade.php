@extends('layouts.app')

@section('title', 'Feeder Sub Type')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')

                <div class="row">
                    {{-- Add Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Feeder Sub Type</div>
                            <div class="card-body">
                                <form action="{{ route('feeder-sub-type.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                                        <select name="feeder_type_id" class="form-control">
                                            <option value="">-- Select Feeder Type --</option>
                                            @foreach ($feederTypes as $type)
                                                <option value="{{ $type->feeder_type_id }}"
                                                    {{ old('feeder_type_id') == $type->feeder_type_id ? 'selected' : '' }}>
                                                    {{ $type->feeder_type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('feeder_type_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('feeder_type_id') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Feeder Sub Type <span style="color:red;">*</span></label>
                                        <input type="text" name="feeder_sub_type" class="form-control"
                                            value="{{ old('feeder_sub_type') }}">
                                        @if ($errors->has('feeder_sub_type'))
                                            <span class="text-danger">
                                                {{ $errors->first('feeder_sub_type') }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- ✅ IMAGE FIELD (OPTIONAL) -->
                                    <div class="mb-3">
                                        <label class="form-label">Image <small class="text-muted">(optional)</small></label>
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

                    {{-- Listing --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <form class="d-flex" method="GET" action="{{ route('feeder-sub-type.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Feeder Sub Type" value="{{ request('search') }}">
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
                                                <th>Feeder Sub Type</th>
                                                <th>Image</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->feeder_sub_type_id }}"></td>
                                                    <td>{{ $row->feederType->feeder_type ?? '' }}</td>
                                                    <td>{{ $row->feeder_sub_type }}</td>
                                                    <td>
                                                        @if ($row->image)
                                                            <img src="{{ asset('uploads/feeder_sub_type/' . $row->image) }}"
                                                                width="50" height="50"
                                                                style="object-fit:cover;border-radius:4px;">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->feeder_sub_type_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->feeder_sub_type_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->feeder_sub_type_id }}">
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


                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('feeder-sub-type.update') }}" enctype="multipart/form-data">
                {{-- ✅ REQUIRED --}}
                @csrf

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Feeder Sub Type</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Feeder Type <span style="color:red;">*</span></label>
                            <select name="feeder_type_id" id="edit_feeder_type_id" class="form-control">
                                @foreach ($feederTypes as $type)
                                    <option value="{{ $type->feeder_type_id }}">
                                        {{ $type->feeder_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Feeder Sub Type <span style="color:red;">*</span></label>
                            <input type="text" name="feeder_sub_type" id="edit_feeder_sub_type" class="form-control">
                        </div>

                        {{-- ✅ EXISTING IMAGE PREVIEW --}}
                        <div class="mb-3" id="editImagePreview" style="display:none;">
                            <label class="form-label">Current Image</label><br>
                            <img id="editImageTag" src="" width="80" height="80"
                                style="object-fit:cover;border:1px solid #ccc;">
                        </div>

                        {{-- ✅ IMAGE INPUT --}}
                        <div class="mb-3">
                            <label class="form-label">Change Image <small>(optional)</small></label>
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

@endsection
@section('scripts')
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

            const ids = selected.map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "{{ route('feeder-sub-type.bulkDelete') }}",
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
        $(document).on('click', '.toggleStatus', function() {
            const btn = $(this);
            const id = btn.data('id');

            if (!id) return;

            $.ajax({
                url: "{{ route('feeder-sub-type.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(res) {
                    if (res.status && btn.length > 0) {
                        const isActive = res.new_status == 1;

                        btn.removeClass('btn-success btn-secondary');
                        btn.addClass(isActive ? 'btn-success' : 'btn-secondary');
                        btn.text(isActive ? 'Active' : 'Inactive');
                    }
                },
                error: function() {
                    alert('Status update failed.');
                }
            });
        });
    </script>


    <script>
        $(document).on('click', '.editBtn', function() {

            const id = $(this).data('id');

            $.get("{{ url('admin/feeder-sub-type/edit') }}/" + id, function(data) {

                $('#edit_id').val(data.feeder_sub_type_id);
                $('#edit_feeder_type_id').val(data.feeder_type_id);
                $('#edit_feeder_sub_type').val(data.feeder_sub_type);
                $('#edit_iStatus').val(data.iStatus);

                // ✅ IMAGE PREVIEW
                if (data.image) {
                    $('#editImageTag').attr(
                        'src',
                        "{{ asset('uploads/feeder_sub_type') }}/" + data.image
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
        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure you want to delete this record?')) return;

            const id = $(this).data('id');

            $.ajax({
                url: "{{ route('feeder-sub-type.destroy') }}",
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
