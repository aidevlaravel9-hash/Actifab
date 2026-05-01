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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <form class="d-flex" method="GET" action="{{ route('feeder-category.index') }}">
                                    <input type="text" name="search" class="form-control me-2"
                                        placeholder="Search Feeder Category Name" value="{{ request('search') }}">
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
                                                <th>Feeder Category Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td><input type="checkbox" class="recordCheckbox"
                                                            value="{{ $row->feeder_category_id }}"></td>
                                                    <td>{{ $row->feeder_category_name }}</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-sm toggleStatus {{ $row->iStatus == 1 ? 'btn-success' : 'btn-secondary' }}"
                                                            data-id="{{ $row->feeder_category_id }}">
                                                            {{ $row->iStatus == 1 ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info editBtn"
                                                            data-id="{{ $row->feeder_category_id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger deleteBtn"
                                                            data-id="{{ $row->feeder_category_id }}">
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
                                            <label class="form-label">Feeder Category Name <span
                                                    style="color:red;">*</span></label>
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
@endsection
