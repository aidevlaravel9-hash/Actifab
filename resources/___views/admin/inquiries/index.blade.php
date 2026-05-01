@extends('layouts.app')

@section('title', 'Inquiry List')

@section('content')

    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                {{--  <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Industry</h4>
                        </div>
                    </div>
                </div>  --}}


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Inquiry List</h5>
                            </div>
                            <div class="card-body">
                                <?php //echo date('ymd');
                                ?>
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th width="5%">Name</th>
                                            <th width="2%">Email</th>
                                            <th width="2%">Subject</th>
                                            <th width="1%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($inquiries as $inquiry)
                                            <tr class="text-center">
                                                <td>{{ $i + $inquiries->perPage() * ($inquiries->currentPage() - 1) }}
                                                <td>{{ $inquiry->name }}</td>
                                                <td>{{ $inquiry->email }}</td>
                                                <td>{{ $inquiry->subject }}</td>
                                                <td>
                                                    <div class="gap-2">
                                                        <a class="mx-1" title="View" href="#"
                                                            onclick="viewData(<?= $inquiry->id ?>)" data-bs-toggle="modal"
                                                            data-bs-target="#ViewModal">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $inquiry->id ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $inquiries->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <!--Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-primary mx-2" data-bs-dismiss="modal">Close</button>
                        <form id="user-delete-form" method="POST" action="{{ route('Inquiry.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="inquiry_id" id="deleteid" value="">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Delete modal End -->

    <!--View Modal Start-->
    <div class="modal fade flip" id="ViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title"> Inquiry Detail </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <div class="model-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%">
                                <tbody>
                                    <tr>
                                        <td class="text-center" style="width: 20%;text-align: justify;padding: 5px;">Name
                                        </td>
                                        <td class="text-center" id="showname"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 20%;text-align: justify;padding: 5px;">Email
                                        </td>
                                        <td class="text-center" id="showemail"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 20%;text-align: justify;padding: 5px;">Subject
                                        </td>
                                        <td class="text-center" id="showsubject"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="width: 20%;padding: 5px;">Message</td>
                                        <td class="text-center" id="showMessage"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--View Modal End -->


@endsection

@section('scripts')
    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }

        function viewData(id) {
            var url = "{{ route('Inquiry.viewdetail', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id,
                        id
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
                        $("#modal-title").html(obj.name);
                        $("#showname").html(obj.name);
                        $("#showsubject").html(obj.subject);
                        $("#showemail").html(obj.email);
                        $("#showMessage").html(obj.message);
                    }
                });
            }
        }
    </script>
@endsection
