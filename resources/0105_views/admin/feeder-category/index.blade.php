@extends('layouts.app')

@section('title', 'Feeder Category')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    {{-- Add Form --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Feeder Category</div>
                            <div class="card-body">
                                <form action="{{ route('feeder-category.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Section <span style="color:red;">*</span></label>
                                        <select name="section_master_id" class="form-control" required>
                                            <option value="" disabled selected>Select Section</option>
                                        
                                            @foreach($sections as $section)
                                                <option value="{{ $section->section_id }}">
                                                    {{ $section->section_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    
                                        @if ($errors->has('section_master_id'))
                                            <span class="text-danger">
                                                {{ $errors->first('section_master_id') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Feeder Category Name <span
                                                style="color:red;">*</span></label>
                                        <input type="text" name="feeder_category_name" class="form-control"
                                            value="{{ old('feeder_category_name') }}">
                                        @if ($errors->has('feeder_category_name'))
                                            <span class="text-danger">
                                                {{ $errors->first('feeder_category_name') }}
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

                    {{-- Listing --}}
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                {{--  <div class="card-header d-flex justify-content-between align-items-center">  --}}
                                {{--  <form class="d-flex" method="GET" action="{{ route('feeder-category.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Feeder Category Name" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-outline-primary me-2">Search</button>
                                    <a href="{{ route('feeder-category.index') }}" class=" btn btn-outline-secondary">
                                        Clear
                                    </a>
                                </form>  --}}
                                <button id="deleteSelected" class="btn btn-danger btn-sm">Bulk Delete</button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="feedercategory" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th width="15%">Action</th>
                                                <th>Section Name</th>
                                                <th>Feeder Category Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr class="text-center">
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->feeder_category_id }}"></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->feeder_category_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            style="width:32px; height:32px;" data-bs-toggle="modal"
                                                            data-bs-target="#deleteRecordModal"
                                                            data-id="{{ $row->feeder_category_id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        {{ $row->section->section_name ?? '-' }}
                                                    </td>
                                                    <td>{{ $row->feeder_category_name }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->feeder_category_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{-- {{ $data->links() }} --}}
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
            <form method="POST" action="{{ route('feeder-category.update') }}">
                @csrf
                <input type="hidden" name="edit_id" id="edit_id">
                
                

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Feeder Category</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Section <span style="color:red;">*</span></label>
                            <select name="section_master_id" id="edit_section_master_id" class="form-control" required>
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->section_id }}">
                                        {{ $section->section_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feeder Category Name <span style="color:red;">*</span></label>
                            <input type="text" name="feeder_category_name" id="edit_feeder_category_name"
                                class="form-control">
                            @if ($errors->has('feeder_category_name'))
                                <span class="text-danger">
                                    {{ $errors->first('feeder_category_name') }}
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal Start -->
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
    <!-- Delete Modal End -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#feedercategory').DataTable({
                pageLength: 25,
                lengthMenu: [ 25, 50, 100 , 125],
                language: {
                    search: "",
                    searchPlaceholder: "Search Feeder Category..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
            });
        });
    </script>
    <script>
        $('.editBtn').on('click', function() {
            const id = $(this).data('id');

            $.get("{{ url('admin/feeder-category/edit') }}/" + id, function(data) {
                $('#edit_id').val(data.feeder_category_id);
                $('#edit_feeder_category_name').val(data.feeder_category_name);
                $('#edit_iStatus').val(data.iStatus);
                
                // 🔥 THIS LINE IS MISSING (MAIN FIX)
                $('#edit_section_master_id').val(data.section_master_id);
        
                $('#editModal').modal('show');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.toggleStatus').on('click', function() {
                const button = $(this);
                const id = button.data('id');

                if (!id) return;

                $.ajax({
                    url: "{{ route('feeder-category.status') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(response) {
                        if (response.status) {
                            if (response.new_status == 1) {
                                button.removeClass('btn-secondary').addClass('btn-success')
                                    .text('Active');
                            } else {
                                button.removeClass('btn-success').addClass('btn-secondary')
                                    .text('Inactive');
                            }
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                    }
                });
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

                var url = "{{ route('feeder-category.destroy', ':id') }}";
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
                        url: "{{ route('feeder-category.bulkDelete') }}",
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
    {{--  <script>
        $(document).ready(function() {

            // Initialize DataTable only once
            let table = $('#example').DataTable({
                columnDefs: [{
                        orderable: false,
                        targets: 0
                    },
                    {
                        orderable: false,
                        targets: 1
                    }
                ]
            });

            // Select All checkbox
            $('#checkAll').on('click', function() {

                let rows = table.rows({
                    search: 'applied'
                }).nodes();

                $('input.recordCheckbox', rows).prop('checked', this.checked);

            });

        });

        $('#deleteSelected').on('click', function() {

            let table = $('#yourTableId').DataTable();

            let selected = table.$('.recordCheckbox:checked');

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
                        url: "{{ route('feeder-category.bulkDelete') }}",
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
    </script>  --}}
@endsection
