@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            {{--  <h4 class="fs-16 mb-1">Admin Login</h4>  --}}
                                        </div>

                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->
                            <div class="row">
                                @php
                                    $feederCount = \App\Models\FeederCategory::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Categories</h5>
                                            <p class="card-text">{{ $feederCount }}</p>
                                            <a href="{{ route('feeder-category.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $typeCount = \App\Models\FeederType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-warning">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Types</h5>
                                            <p class="card-text">{{ $typeCount }}</p>
                                            <a href="{{ route('feeder-type.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $subTypeCount = \App\Models\FeederSubType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Feeder Sub Types</h5>
                                            <p class="card-text">{{ $subTypeCount }}</p>
                                            <a href="{{ route('feeder-sub-type.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $panelCount = \App\Models\PanelType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Panel Types</h5>
                                            <p class="card-text">{{ $panelCount }}</p>
                                            <a href="{{ route('panel-type.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $partsCount = \App\Models\PartsCategory::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-secondary">
                                        <div class="card-body">
                                            <h5 class="card-title">Parts Categories</h5>
                                            <p class="card-text">{{ $partsCount }}</p>
                                            <a href="{{ route('parts-category.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $partsTotal = \App\Models\PartsMaster::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Parts Master</h5>
                                            <p class="card-text">{{ $partsTotal }}</p>
                                            <a href="{{ route('parts-master.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                {{--  @php
                                    $projectTotal = \App\Models\Project::where('status', 1)
                                    ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5 class="card-title">Project</h5>
                                            <p class="card-text">{{ $projectTotal }}</p>
                                            <a href="{{ route('project.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>  --}}

                                @php
                                    $poleCount = \App\Models\NoOfPole::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">No of Poles</h5>
                                            <p class="card-text">{{ $poleCount }}</p>
                                            <a href="{{ route('poles.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $voltageCount = \App\Models\OperatingVoltage::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Operating Voltage</h5>
                                            <p class="card-text">{{ $voltageCount }}</p>
                                            <a href="{{ route('voltage.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $formTypeCount = \App\Models\FormType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Form Type</h5>
                                            <p class="card-text">{{ $formTypeCount }}</p>
                                            <a href="{{ route('form-type.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $panelAccessCount = \App\Models\PanelAccess::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5 class="card-title">Panel Access</h5>
                                            <p class="card-text">{{ $panelAccessCount }}</p>
                                            <a href="{{ route('panel-access.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $colourCount = \App\Models\PanelBoardColour::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Board Colour</h5>
                                            <p class="card-text">{{ $colourCount }}</p>
                                            <a href="{{ route('panel-board-colour.index') }}"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $ipProtectionCount = \App\Models\IpProtection::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">IP Protection</h5>
                                            <p class="card-text">{{ $ipProtectionCount }}</p>
                                            <a href="{{ route('ip-protection.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $lockSystemCount = \App\Models\LockSystem::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Lock System</h5>
                                            <p class="card-text">{{ $lockSystemCount }}</p>
                                            <a href="{{ route('lock-system.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $busbarPositionCount = \App\Models\BusbarPosition::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp

                                <div class="col-md-3">
                                    <div class="card text-white bg-info">
                                        <div class="card-body">
                                            <h5>Busbar Position</h5>
                                            <h3>{{ $busbarPositionCount }}</h3>
                                            <a href="{{ route('busbar-position.index') }}"
                                                class="btn btn-light btn-sm">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $gptCount = \App\Models\GlandPlateThickness::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Gland Plate Thickness</h5>
                                            <p class="card-text">{{ $gptCount }}</p>
                                            <a href="{{ route('gland-plate-thickness.index') }}"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $ocpCount = \App\Models\OutgoingCablePosition::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Outgoing Cable Position</h5>
                                            <p class="card-text">{{ $ocpCount }}</p>
                                            <a href="{{ route('outgoing-cable-position.index') }}"
                                                class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $plinthTypeCount = \App\Models\PlinthType::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card text-white bg-dark">
                                        <div class="card-body">
                                            <h5 class="card-title">Plinth Type</h5>
                                            <p class="card-text">{{ $plinthTypeCount }}</p>
                                            <a href="{{ route('plinth-type.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $certificationCount = \App\Models\Certification::where('iStatus', 1)
                                        ->where('isDelete', 0)
                                        ->count();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Certifications</h5>
                                            <p class="card-text">{{ $certificationCount }}</p>
                                            <a href="{{ route('certification.index') }}" class="btn btn-light">Manage</a>
                                        </div>
                                    </div>
                                </div>





                            </div>





                        </div>
                    </div>

                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ env('APP_NAME') }}
                    </div>

                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->


@endsection
