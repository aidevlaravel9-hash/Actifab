@extends('layouts.user')

@section('title', isset($panelBoard) ? 'Edit Panel Board' : 'Add Panel Board')

@section('content')

<style>
.form-inline-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-inline-group label {
    min-width: 140px;
    margin-bottom: 0;
}
</style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                {{-- Page Title --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">
                                {{ isset($panelBoard) ? 'Edit Panel Board' : 'Add Panel Board' }}
                            </h4>
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

                                <form method="POST"
                                    action="{{ isset($panelBoard) ? route('UpdatePanelBoard', $panelBoard->id) : route('StorePanelBoard') }}">
                                    @csrf

                                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                                    {{-- ================= TOP SECTION ================= --}}
                                    <div class="row mb-4">

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Inquiry No.</label>
                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $project->inquiry_no }}" readonly>
                                        </div>

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Panel Board Job No</label>
                                            <input type="text" id="panel_job_no" name="panel_board_job_no"
                                                value="{{ old('panel_board_job_no', $panelBoard->panel_board_job_no ?? '') }}"
                                                class="form-control form-control-sm w-75" readonly>
                                        </div>

                                        <div class="col-md-4 form-inline-group">
                                            <label class="form-label">Panel Board Name</label>
                                            <input type="text" name="panel_board_name"
                                                value="{{ old('panel_board_name', $panelBoard->panel_board_name ?? '') }}"
                                                class="form-control form-control-sm w-75" required>
                                        </div>

                                    </div>

                                    <hr>

                                    {{-- ================= ELECTRICAL SECTION ================= --}}
                                    <div class="row">

                                        {{-- System Current Rating --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">System Current Rating</label>
                                            <select name="system_current_rating_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Rating</option>
                                                @foreach ($systemRatings as $rating)
                                                    <option value="{{ $rating->system_current_rating_id }}"
                                                        {{ old('system_current_rating_id', $panelBoard->system_current_rating_id ?? '') == $rating->system_current_rating_id ? 'selected' : '' }}>
                                                        {{ $rating->rating }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- No of Poles --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">No of Poles</label>
                                            <select name="no_of_poles_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Poles</option>
                                                @foreach ($noOfPoles as $pole)
                                                    <option value="{{ $pole->no_of_poles_id }}"
                                                        {{ old('no_of_poles_id', $panelBoard->no_of_poles_id ?? '') == $pole->no_of_poles_id ? 'selected' : '' }}>
                                                        {{ $pole->no_of_poles }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Type of Panel --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Type of Panel</label>
                                            <select name="type_of_panel_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Panel Type</option>
                                                @foreach ($panelTypes as $type)
                                                    <option value="{{ $type->panel_type_id }}"
                                                        {{ old('type_of_panel_id', $panelBoard->type_of_panel_id ?? '') == $type->panel_type_id ? 'selected' : '' }}>
                                                        {{ $type->panel_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Operating Voltage --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Operating Voltage</label>
                                            <select name="operating_voltage_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Voltage</option>
                                                @foreach ($operatingVoltages as $voltage)
                                                    <option value="{{ $voltage->operating_voltage_id }}"
                                                        {{ old('operating_voltage_id', $panelBoard->operating_voltage_id ?? '') == $voltage->operating_voltage_id ? 'selected' : '' }}>
                                                        {{ $voltage->operating_voltage }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Form Type --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Form Type</label>
                                            <select name="form_type_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Form Type</option>
                                                @foreach ($formTypes as $form)
                                                    <option value="{{ $form->form_type_id }}"
                                                        {{ old('form_type_id', $panelBoard->form_type_id ?? '') == $form->form_type_id ? 'selected' : '' }}>
                                                        {{ $form->form_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Panel Access --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Panel Access</label>
                                            <select name="panel_access_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Panel Access</option>
                                                @foreach ($panelAccesses as $access)
                                                    <option value="{{ $access->panel_access_id }}"
                                                        {{ old('panel_access_id', $panelBoard->panel_access_id ?? '') == $access->panel_access_id ? 'selected' : '' }}>
                                                        {{ $access->panel_access }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Panel Board Colour --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Panel Board Colour</label>
                                            <select name="panel_board_colour_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Colour</option>
                                                @foreach ($panelColours as $colour)
                                                    <option value="{{ $colour->panel_board_colour_id }}"
                                                        {{ old('panel_board_colour_id', $panelBoard->panel_board_colour_id ?? '') == $colour->panel_board_colour_id ? 'selected' : '' }}>
                                                        {{ $colour->panel_board_colour }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- IP Protection --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">IP Protection</label>
                                            <select name="ip_protection_id" class="form-select form-select-sm w-75">
                                                <option value="">Select IP Protection</option>
                                                @foreach ($ipProtections as $ip)
                                                    <option value="{{ $ip->ip_protection_id }}"
                                                        {{ old('ip_protection_id', $panelBoard->ip_protection_id ?? '') == $ip->ip_protection_id ? 'selected' : '' }}>
                                                        {{ $ip->ip_protection }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    {{-- ================= MECHANICAL SECTION ================= --}}
                                    <div class="row">

                                        {{-- Frame Height --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Height (Without Plinth)</label>
                                            <input type="text" name="frame_height"
                                                value="{{ old('frame_height', $panelBoard->frame_height ?? '') }}"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        {{-- Lock System --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Lock System</label>
                                            <select name="lock_system_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Lock System</option>
                                                @foreach ($lockSystems as $lock)
                                                    <option value="{{ $lock->lock_system_id }}"
                                                        {{ old('lock_system_id', $panelBoard->lock_system_id ?? '') == $lock->lock_system_id ? 'selected' : '' }}>
                                                        {{ $lock->lock_system }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            
                                        </div>
                                        
                                        {{-- Frame Width --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Width</label>
                                            <input type="text" name="frame_width"
                                                value="{{ old('frame_width', $panelBoard->frame_width ?? '') }}"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        {{-- Busbar Position --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Busbar Position</label>
                                            <select name="busbar_position_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Busbar Position</option>
                                                @foreach ($busbarPositions as $position)
                                                    <option value="{{ $position->busbar_position_id }}"
                                                        {{ old('busbar_position_id', $panelBoard->busbar_position_id ?? '') == $position->busbar_position_id ? 'selected' : '' }}>
                                                        {{ $position->busbar_position }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        
                                        {{-- Gland Plate Thickness --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Gland Plate Thickness</label>
                                            <select name="gland_plate_thickness_id"
                                                class="form-select form-select-sm w-75">
                                                <option value="">Select Thickness</option>
                                                @foreach ($glandPlateThicknesses as $thickness)
                                                    <option value="{{ $thickness->gland_plate_thickness_id }}"
                                                        {{ old('gland_plate_thickness_id', $panelBoard->gland_plate_thickness_id ?? '') == $thickness->gland_plate_thickness_id ? 'selected' : '' }}>
                                                        {{ $thickness->gland_plate_thickness }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        {{-- Frame Depth --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Frame Depth</label>
                                            <input type="text" name="frame_depth"
                                                value="{{ old('frame_depth', $panelBoard->frame_depth ?? '') }}"
                                                class="form-control form-control-sm w-75">
                                        </div>  

                                        {{-- Outgoing Cable Position --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Outgoing Cable Position</label>
                                            <select name="outgoing_cable_position_id"
                                                class="form-select form-select-sm w-75">
                                                <option value="">Select Cable Position</option>
                                                @foreach ($outgoingCablePositions as $position)
                                                    <option value="{{ $position->outgoing_cable_position_id }}"
                                                        {{ old('outgoing_cable_position_id', $panelBoard->outgoing_cable_position_id ?? '') == $position->outgoing_cable_position_id ? 'selected' : '' }}>
                                                        {{ $position->outgoing_cable_position }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Thickness --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Thickness – Door / Cover / Other</label>
                                            <input type="text" name="thickness"
                                                value="{{ old('thickness', $panelBoard->thickness ?? '') }}"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        {{-- Plinth Height --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Plinth Height</label>
                                            <input type="text" name="plinth_height"
                                                value="{{ old('plinth_height', $panelBoard->plinth_height ?? '') }}"
                                                class="form-control form-control-sm w-75">
                                        </div>

                                        {{-- Plinth Type --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Plinth Type</label>
                                            <select name="plinth_type_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Plinth Type</option>
                                                @foreach ($plinthTypes as $plinth)
                                                    <option value="{{ $plinth->plinth_type_id }}"
                                                        {{ old('plinth_type_id', $panelBoard->plinth_type_id ?? '') == $plinth->plinth_type_id ? 'selected' : '' }}>
                                                        {{ $plinth->plinth_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        {{-- Certification --}}
                                        <div class="col-md-4 mb-4 form-inline-group">
                                            <label class="form-label">Certification</label>
                                            <select name="certification_id" class="form-select form-select-sm w-75">
                                                <option value="">Select Certification</option>
                                                @foreach ($certifications as $cert)
                                                    <option value="{{ $cert->certification_id }}"
                                                        {{ old('certification_id', $panelBoard->certification_id ?? '') == $cert->certification_id ? 'selected' : '' }}>
                                                        {{ $cert->certification }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="mt-3 text-end">
                                        <button type="submit" class="btn btn-success px-4">
                                            {{ isset($panelBoard) ? 'Update' : 'Submit' }}
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

    @if (!isset($panelBoard))

    // ✅ Dynamic correct URL (handles /Actifab automatically)
    let url = "{{ url('user-panel/get-panel-job-no') }}/" + projectId;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(text => {
        try {
            let data = JSON.parse(text);
            jobInput.value = data.job_no || '';
        } catch (e) {
            console.error('Invalid JSON:', text);
            jobInput.value = '';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        jobInput.value = '';
    });

    @endif

});
</script>
@endsection
