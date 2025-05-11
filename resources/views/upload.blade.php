@extends ('layouts.app')

@section('content')

<h1>Upload CSV File</h1>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Upload File</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('uploadStore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" class="form-control" name="file" id="file" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Upload</button>
            </form>
        </div>
    </div>
</div>

<hr>
<h2>Uploaded Files</h2>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col">Time</th>
                <th scope="col">File Name</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody id="upload-status-body">
            <!-- Rows injected by JavaScript -->
        </tbody>
    </table>
</div>


<script>
    function fetchStatus() {
        fetch('{{ route('uploadStatus') }}')
            .then(response => response.json())
            .then(({ data }) => {
                const tbody = document.getElementById('upload-status-body');
                tbody.innerHTML = ''; // clear old rows

                data.forEach(row => {
                                        const tr = document.createElement('tr');
                                          let statusClass = 'text-warning'; // Default: Pending
                                            if (row.status === 'Completed') statusClass = 'text-success';
                                            else if (row.status === 'Failed') statusClass = 'text-danger';
                                            else if (row.status === 'Processed') statusClass = 'text-primary';

                                           tr.innerHTML = `
                                                            <td>
                                                                ${row.created_at}<br>
                                                                <small class="text-muted">(${row.human_time})</small>
                                                            </td>
                                                            <td>${row.file_name}</td>
                                                            <td class="fw-bold ${statusClass}">${row.status}</td>
                                                        `;

                                        tbody.appendChild(tr);
                                    });

            });
    }

    // Initial fetch
    fetchStatus();

    // Poll every 5 seconds
    setInterval(fetchStatus, 5000);
</script>
    
@endsection