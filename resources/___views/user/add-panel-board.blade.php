@extends('layouts.user')

@section('title', 'Add Panel Board')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                {{-- Page Title --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Panel Board</h4>
                            <div class="page-title-right">
                                <a href="{{ route('PanelListing', $project->id) }}" class="btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <form method="POST" action="{{ route('StorePanelBoard') }}">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                                    {{-- ================= TOP SECTION ================= --}}
                                    <div class="row mb-4">

                                        <div class="col-md-4">
                                            <label class="form-label"> Inquiry No.</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $project->inquiry_no }}" readonly>

                                            {{--  <select name="project_id" id="project_id"
                                                class="form-select form-select-sm w-75">
                                                <option value="">Select Inquiry</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">
                                                        {{ $project->inquiry_no }}
                                                    </option>
                                                @endforeach
                                            </select>  --}}
                                        </div>


                                        <div class="col-md-4">
                                            <label class="form-label">Panel Board Job No</label>
                                            <input type="text" id="panel_job_no" name="panel_board_job_no"
                                                class="form-control form-control-sm w-75" readonly>
                                        </div>


                                        <div class="col-md-4">
                                            <label class="form-label">Panel Board Name</label>
                                            <input type="text" name="panel_board_name"
                                                class="form-control form-control-sm w-75" required>

                                        </div>

                                    </div>

                                    <hr>

                                    {{-- ================= ELECTRICAL SECTION ================= --}}
                                    <div class="row">

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">System Current Rating</label>
                                            <select name="system_current_rating_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Rating</option>

                                                @foreach ($systemRatings as $rating)
                                                    <option value="{{ $rating->system_current_rating_id }}">
                                                        {{ $rating->rating }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">No of Poles</label>
                                            <select name="no_of_poles_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Poles</option>

                                                @foreach ($noOfPoles as $pole)
                                                    <option value="{{ $pole->no_of_poles_id }}">
                                                        {{ $pole->no_of_poles }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Type of Panel</label>
                                            <select name="type_of_panel_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Panel Type</option>

                                                @foreach ($panelTypes as $type)
                                                    <option value="{{ $type->panel_type_id }}">
                                                        {{ $type->panel_type }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Operating Voltage</label>
                                            <select name="operating_voltage_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Voltage</option>

                                                @foreach ($operatingVoltages as $voltage)
                                                    <option value="{{ $voltage->operating_voltage_id }}">
                                                        {{ $voltage->operating_voltage }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Form Type</label>
                                            <select name="form_type_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Form Type</option>

                                                @foreach ($formTypes as $form)
                                                    <option value="{{ $form->form_type_id }}">
                                                        {{ $form->form_type }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Panel Access</label>
                                            <select name="panel_access_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Panel Access</option>

                                                @foreach ($panelAccesses as $access)
                                                    <option value="{{ $access->panel_access_id }}">
                                                        {{ $access->panel_access }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Panel Board Colour</label>
                                            <select name="panel_board_colour_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Colour</option>

                                                @foreach ($panelColours as $colour)
                                                    <option value="{{ $colour->panel_board_colour_id }}">
                                                        {{ $colour->panel_board_colour }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">IP Protection</label>
                                            <select name="ip_protection_id" class="form-select form-select-sm w-75">

                                                <option value="">Select IP Protection</option>

                                                @foreach ($ipProtections as $ip)
                                                    <option value="{{ $ip->ip_protection_id }}">
                                                        {{ $ip->ip_protection }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                    </div>

                                    {{--  <hr>  --}}

                                    {{-- ================= MECHANICAL SECTION ================= --}}
                                    <div class="row">

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Frame Height (Without Plinth)</label>
                                            <input type="text" name="frame_height"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Lock System</label>
                                            <select name="lock_system_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Lock System</option>

                                                @foreach ($lockSystems as $lock)
                                                    <option value="{{ $lock->lock_system_id }}">
                                                        {{ $lock->lock_system }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Busbar Position</label>
                                            <select name="busbar_position_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Busbar Position</option>

                                                @foreach ($busbarPositions as $position)
                                                    <option value="{{ $position->busbar_position_id }}">
                                                        {{ $position->busbar_position }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Frame Width</label>
                                            <input type="text" name="frame_width"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Gland Plate Thickness</label>
                                            <select name="gland_plate_thickness_id"
                                                class="form-select form-select-sm w-75">

                                                <option value="">Select Thickness</option>

                                                @foreach ($glandPlateThicknesses as $thickness)
                                                    <option value="{{ $thickness->gland_plate_thickness_id }}">
                                                        {{ $thickness->gland_plate_thickness }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Outgoing Cable Position</label>
                                            <select name="outgoing_cable_position_id"
                                                class="form-select form-select-sm w-75">

                                                <option value="">Select Cable Position</option>

                                                @foreach ($outgoingCablePositions as $position)
                                                    <option value="{{ $position->outgoing_cable_position_id }}">
                                                        {{ $position->outgoing_cable_position }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Frame Depth</label>
                                            <input type="text" name="frame_depth"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Plinth Height</label>
                                            <input type="text" name="plinth_height"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Plinth Type</label>
                                            <select name="plinth_type_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Plinth Type</option>

                                                @foreach ($plinthTypes as $plinth)
                                                    <option value="{{ $plinth->plinth_type_id }}">
                                                        {{ $plinth->plinth_type }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Thickness – Door / Cover / Other</label>
                                            <input type="text" name="thickness"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        <div class="col-md-4 mb-4">
                                            <label class="form-label">Certification</label>
                                            <select name="certification_id" class="form-select form-select-sm w-75">

                                                <option value="">Select Certification</option>

                                                @foreach ($certifications as $cert)
                                                    <option value="{{ $cert->certification_id }}">
                                                        {{ $cert->certification }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>


                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-success px-4">
                                            Submit
                                        </button>
                                    </div>

                                </form>

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
document.addEventListener('DOMContentLoaded', function () {

    let jobInput = document.getElementById('panel_job_no');
    let projectId = "{{ $project->id }}";

    fetch("{{ route('get.panel.job.no', $project->id) }}")
        .then(response => response.json())
        .then(data => {
            jobInput.value = data.job_no;
        })
        .catch(err => console.log(err));

});
</script>

    // <script>
    //     document.addEventListener('DOMContentLoaded', function() {

    //         let jobInput = document.getElementById('panel_job_no');
    //         let projectId = "{{ $project->id }}";

    //         fetch('/get-panel-job-no/' + projectId)
    //             .then(response => response.json())
    //             .then(data => {
    //                 jobInput.value = data.job_no;
    //             });

    //     });
    // </script>

    {{--  <script>
        document.getElementById('project_id').addEventListener('change', function() {

            let projectId = this.value;

            if (projectId != '') {

                fetch('/get-panel-job-no/' + projectId)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('panel_job_no').value = data.job_no;
                    });
            }
        });
    </script>  --}}
@endsection
