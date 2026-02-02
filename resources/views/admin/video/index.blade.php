@extends('layouts.app')
@section('title', 'Video List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">

                        <div class="card">

                            <div class="card-body">
                                <div class="row">


                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between card-header">
                                            <h5 class="card-title mb-0">Video List</h5>
                                        </div>

                                        <table id="scroll-horizontal" class="table nowrap align-middle mt-3"
                                            style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    <th>Url</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($datas as $data)
                                                    <tr class="text-center">
                                                        <td>{{ $i + $datas->perPage() * ($datas->currentPage() - 1) }}
                                                        </td>
                                                        <td>
                                                            <a target="_blank" href="{{ $data->url }}">
                                                                {{ $data->url }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <div class="gap-2">
                                                                <a class="mx-1" title="Edit" href="#"
                                                                    onclick="getEditData(<?= $data->id ?>)"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#showModal">
                                                                    <i class="far fa-edit"></i>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $datas->links() }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>
                </div>


<!--Edit Modal Start-->
        <div class="modal fade flip" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal"></button>
                    </div>
                    <form method="POST" action="{{ route('video.update') }}"
                        autocomplete="off" >
                        @csrf
                        <input type="hidden" name="id" id="video_id" value="">

                        <div class="modal-body">

                            <div class="mb-3">
                                <span style="color:red;">*</span>Url
                                <input type="text" class="form-control" name="url" id="Editurl"
                                    placeholder="Enter URL" autocomplete="off" required>
                                @error('url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-success" id="add-btn">Update</button>
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Edit Modal End -->

            </div>
        </div>
    </div>
@endsection

@section('scripts')

   <script>
            function getEditData(id) {
                var url = "{{ route('video.edit', ':id') }}";
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
                            $("#Editurl").val(obj.url);
                            $('#video_id').val(id);
                        }
                    });
                }
            }
        </script>

@endsection
