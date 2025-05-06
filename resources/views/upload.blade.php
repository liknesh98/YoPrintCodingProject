@extends ('layouts.app')

@section('content')

<h1>Upload CSV File</h1>

<form action="{{ route('uploadStore') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

<hr>
<h2>Uploaded Files</h2>

<table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Time</th>
                <th>File Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="upload-status-body">
        </tbody>
    </table>


<script>
    function fetchStatus() {
        fetch('{{ route('uploadStatus') }}')
            .then(response => response.json())
            .then(({ data }) => {
                const tbody = document.getElementById('upload-status-body');
                tbody.innerHTML = ''; // clear old rows

                data.forEach(row => {
                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td>${row.created_at}<br>(${row.human_time})</td>
                        <td>${row.file_name}</td>
                        <td>${row.status}</td>
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