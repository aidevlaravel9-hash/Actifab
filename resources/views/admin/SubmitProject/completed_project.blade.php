@extends('layouts.app')

@section('title', 'Complete Project List')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    {{-- Listing --}}
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="table-responsive">

                                    <table id="feedercategory" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Project Name</th>
                                                <th>Panel Name</th>
                                                <th>Customer Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($panels as $panel)
                                                <tr>
                                                    <td>{{ $panel->project->project_name ?? '-' }}</td>
                                                    <td>{{ $panel->panel_board_name }}</td>
                                                    <td>{{ $panel->project->customer_name ?? '-' }}</td>

                                                    <td>
                                                        <a href="{{ route('admin.panel.section.list', $panel->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            View Section
                                                        </a>
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

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#feedercategory').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    search: "",
                    searchPlaceholder: "Search Project ..."
                },
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6 text-end"p>>'
            });
        });
    </script>


@endsection
