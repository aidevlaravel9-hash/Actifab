@extends('layouts.user')

@section('title', 'Create Project')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Create Project
                            <a href="{{ route('CreateProject') }}" class="btn btn-sm btn-primary float-end">
                                <i class="fas fa-plus"></i> Add New
                            </a>
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Bulk Delete --}}
                        {{--  <div class="mb-3">
                            <button class="btn btn-danger btn-sm" id="bulkDeleteBtn">
                                <i class="fas fa-trash"></i> Bulk Delete
                            </button>
                        </div>  --}}

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        {{--  <th width="3%">
                                            <input type="checkbox" id="checkAll">
                                        </th>  --}}
                                        <th>Inquiry No</th>
                                        <th>Project Name</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projects as $project)
                                        <tr>
                                            {{--  <td>
                                                <input type="checkbox" class="rowCheck" value="{{ $project->id }}">
                                            </td>  --}}

                                            <td>{{ $project->inquiry_no }}</td>

                                            <td>{{ $project->project_name }}</td>

                                            <td>{{ $project->customer_name }}</td>

                                            <td>{{ \Carbon\Carbon::parse($project->inquiry_date)->format('d-m-Y') }}</td>

                                            <td>
                                                {{-- Status Toggle --}}
                                                @if ($project->status == 1)
                                                    <a href="{{ route('project.toggleStatus', $project->id) }}"
                                                        class="badge bg-success"
                                                        onclick="return confirm('Make this project Inactive?')">
                                                        Active
                                                    </a>
                                                @else
                                                    <a href="{{ route('project.toggleStatus', $project->id) }}"
                                                        class="badge bg-danger"
                                                        onclick="return confirm('Make this project Active?')">
                                                        Inactive
                                                    </a>
                                                @endif

                                                {{-- Manage Panel Button --}}
                                                <a href="{{ route('PanelListing', $project->id) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    Manage Panel
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No Projects Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $projects->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
