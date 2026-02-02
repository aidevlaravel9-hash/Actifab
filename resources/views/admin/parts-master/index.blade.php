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
                                <form action="{{ route('parts-master.store') }}" method="POST">
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
                                                <th>Category</th>
                                                <th>Part Name</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->parts_id }}"></td>
                                                    <td>{{ $row->category->parts_category_name ?? '' }}</td>
                                                    <td>{{ $row->parts_name }}</td>
                                                    <td>{{ $row->part_amount }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->parts_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->parts_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->parts_id }}">
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
            <form method="POST" action="{{ route('parts-master.update') }}">
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
                                    <option value="{{ $cat->parts_id }}">{{ $cat->parts_category_name }}</option>
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
                $('#editModal').modal('show');
            });
        });

        $(document).on('click', '.deleteBtn', function() {
            if (!confirm('Are you sure you want to delete this record?')) return;
            const id = $(this).data('id');
            $.post("{{ route('parts-master.destroy') }}", {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(res) {
                if (res.status) location.reload();
            });
        });

        $('#checkAll').on('click', function() {
            $('.recordCheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#deleteSelected').on('click', function() {
            const selected = $('.recordCheckbox:checked');
            if (selected.length === 0) return alert('Select at least one record.');
            if (!confirm('Are you sure to delete selected records?')) return;
            const ids = selected.map(function() {
                return $(this).val();
            }).get();
            $.post("{{ route('parts-master.bulkDelete') }}", {
                _token: '{{ csrf_token() }}',
                ids: ids
            }, function(res) {
                if (res.status) location.reload();
            });
        });
    </script>
@endsection
