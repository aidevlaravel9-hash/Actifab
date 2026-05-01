@extends('layouts.user')

@section('title', 'Panel Listing')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Project Name - {{ $project->project_name }}

                            <a href="{{ route('AddPanelBoard', $project->id) }}" class="btn btn-sm btn-primary float-end">
                                <i class="fas fa-plus"></i> Add Panel
                            </a>
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Panel Name</th>
                                        <th>Panel Type</th>
                                        <th>Created Date</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">

                                    @forelse($panels as $panel)
                                        <tr>

                                            <td>{{ $panel->panel_board_name }}</td>

                                            <td>{{ $panel->panelType->panel_type ?? '-' }}</td>


                                            <td>{{ \Carbon\Carbon::parse($panel->created_at)->format('d-m-Y') }}</td>

                                            <td>
                                                <div class="d-flex gap-2">

                                                    <a href="{{ route('panel.show', $panel->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        Add Section
                                                    </a>

                                                    {{-- Edit --}}
                                                    {{--  <a href="{{ route('EditPanelBoard', $panel->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>  --}}

                                                    {{-- Delete --}}
                                                    {{--  <a href="{{ route('DeletePanelBoard', $panel->id) }}"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure to delete this panel?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>  --}}

                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                No Panels Found
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $panels->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
