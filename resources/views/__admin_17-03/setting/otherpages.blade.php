@extends('layouts.app')
@section('title', 'Setting List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Setting List</h5>
                            </div>
                            <div class="card-body">

                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Page Name</th>
                                            {{--  <th width="50%">Description</th>  --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($Setting as $data)
                                            <tr class="text-center">
                                                <td>{{ $i + $Setting->perPage() * ($Setting->currentPage() - 1) }}
                                                </td>
                                                <td>{{ $data->pagename }}</td>
                                                {{--  <td>
                                                    {!! Str::limit($data->description, 205, '....') !!}
                                                </td>  --}}
                                                <td>
                                                    <div class=" gap-2">
                                                        <a class="mx-1" title="Edit" href="#"
                                                            onclick="getEditData(<?= $data->id ?>)" data-bs-toggle="modal"
                                                            data-bs-target="#showModal">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <a class="mx-1" title="View" href="#"
                                                            onclick="viewData(<?= $data->id ?>)" data-bs-toggle="modal"
                                                            data-bs-target="#ViewModal">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Setting->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Edit Modal Start-->
                <div class="modal fade flip" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Settings</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form onsubmit="return EditvalidateFile()" method="POST"
                                action="{{ route('otherpages.update') }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="settingid" value="">

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <span style="color:red;">*</span>Page Name
                                        <input type="text" class="form-control" name="pagename" id="Editpagename"
                                            placeholder="Enter Page Name" autocomplete="off" required>
                                    </div>

                                    <div class="mb-3">
                                        <span style="color:red;">*</span>Description
                                        <textarea class="form-control ckeditor" name="description" id="Editdescription" rows="6" required></textarea>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary mx-2" id="add-btn">Update</button>
                                        <button type="button" class="btn btn-primary mx-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--Edit Modal End -->

                <!--View Modal Start-->
                <div class="modal fade flip" id="ViewModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title"> Page Name :- &nbsp; </h5>
                                <h5 class="modal-title" id="modal-title"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <div class="model-body">
                                <div class="row">
                                  <div class="col-lg-12">
                                      <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="text-center" style="width: 20%;">Description</td>
                                            <td id="showdescription"
                                                style="padding: 9px;
                                            
                                            text-align: justify;">
                                            </td>
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

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>

    <script>
        function getEditData(id) {
            var url = "{{ route('otherpages.edit', ':id') }}";
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
                        //console.log(data);
                        var obj = JSON.parse(data);
                        $("#Editpagename").val(obj.pagename);
                        CKEDITOR.instances['Editdescription'].setData(obj.description);
                        $('#settingid').val(id);
                    }
                });
            }
        }

        function viewData(id) {
            var url = "{{ route('otherpages.viewdetail', ':id') }}";
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
                        $("#modal-title").html(obj.pagename);
                        $("#showpagename").html(obj.pagename);
                        $("#showdescription").html(obj.description);
                    }
                });
            }
        }
    </script>




@endsection
