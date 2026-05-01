@extends('layouts.user')

@section('title', 'Add Panel Board – Sections & Rows')

@section('content')

    <h4 class="mb-4 font-weight-bold">Add Panel Board – Sections & Rows (Demo)</h4>

    {{-- PANEL SUMMARY --}}
    <div class="p-3 mb-4 bg-white border rounded">
        <div class="row">
            <div class="col-md-4"><strong>Panel Width:</strong> 1000</div>
            <div class="col-md-4"><strong>Panel Height:</strong> 800</div>
            <div class="col-md-4"><strong>Panel Depth:</strong> 800</div>
        </div>
    </div>

    {{-- SECTION WIDTH SPLIT --}}
    <div class="p-3 mb-4 bg-white border rounded">
        <h6 class="font-weight-bold mb-3">Section Width Split</h6>

        <p>
            Remaining Width :
            <span class="text-success font-weight-bold" id="remainingWidth">1000</span>
        </p>

        <div class="form-inline mb-3">
            <input type="number" id="sectionWidthInput" class="form-control mr-2" placeholder="Enter Section Width">
            <button type="button" class="btn btn-primary" onclick="addSection()">
                Add Section
            </button>
        </div>

        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>Section No</th>
                    <th>Section Width</th>
                </tr>
            </thead>
            <tbody id="sectionsTable"></tbody>
        </table>
    </div>

    {{-- ROW HEIGHT SPLIT --}}
    <div class="p-3 mb-4 bg-white border rounded">
        <h6 class="font-weight-bold mb-3">Row Height Split (Inside Section)</h6>

        <p>
            Remaining Height :
            <span class="text-success font-weight-bold" id="remainingHeight">800</span>
        </p>

        <div class="form-inline mb-3">
            <input type="number" id="rowHeightInput" class="form-control mr-2" placeholder="Enter Row Height">
            <button type="button" class="btn btn-primary" onclick="addRow()">
                Add Row
            </button>
        </div>

        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>Row No</th>
                    <th>Row Height</th>
                </tr>
            </thead>
            <tbody id="rowsTable"></tbody>
        </table>
    </div>

    <button class="btn btn-success px-5">SUBMIT</button>

@endsection


@push('scripts')
    <script>
        /* =====================
           SECTION WIDTH LOGIC
        ====================== */
        let totalWidth = 1000;
        let usedWidth = 0;
        let sectionCount = 0;

        function addSection() {
            let input = document.getElementById('sectionWidthInput');
            let width = parseInt(input.value);

            if (!width || width <= 0) {
                alert('Please enter valid section width');
                return;
            }

            let remaining = totalWidth - usedWidth;

            if (width > remaining) {
                alert('Section width exceeds remaining width');
                return;
            }

            sectionCount++;
            usedWidth += width;

            document.getElementById('sectionsTable').insertAdjacentHTML('beforeend', `
            <tr>
                <td>${sectionCount}</td>
                <td>${width}</td>
            </tr>
        `);

            document.getElementById('remainingWidth').innerText = totalWidth - usedWidth;
            input.value = '';
        }

        /* =====================
           ROW HEIGHT LOGIC
        ====================== */
        let totalHeight = 800;
        let usedHeight = 0;
        let rowCount = 0;

        function addRow() {
            let input = document.getElementById('rowHeightInput');
            let height = parseInt(input.value);

            if (!height || height <= 0) {
                alert('Please enter valid row height');
                return;
            }

            let remaining = totalHeight - usedHeight;

            if (height > remaining) {
                alert('Row height exceeds remaining height');
                return;
            }

            rowCount++;
            usedHeight += height;

            document.getElementById('rowsTable').insertAdjacentHTML('beforeend', `
            <tr>
                <td>${rowCount}</td>
                <td>${height}</td>
            </tr>
        `);

            document.getElementById('remainingHeight').innerText = totalHeight - usedHeight;
            input.value = '';
        }
    </script>
@endpush
