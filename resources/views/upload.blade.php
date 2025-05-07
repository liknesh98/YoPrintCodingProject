@extends ('layouts.app')

@section('content')

<h1>Upload CSV File</h1>

<div class="p-6 bg-white rounded-lg shadow-md w-full max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-semibold mb-4">Upload File</h2>
    
    <form action="{{ route('uploadStore') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
        @csrf

        <input type="file" name="file" required class="border rounded p-2 shadow-sm focus:ring-2 focus:ring-blue-500">

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Upload
        </button>
    </form>
</div>
<hr>
<h2>Uploaded Files</h2>

<table class="min-w-full table-auto border border-gray-200 shadow-sm rounded-md">
    <thead class="bg-gray-100 text-gray-700">
        <tr>
            <th class="px-4 py-2 border">Time</th>
            <th class="px-4 py-2 border">File Name</th>
            <th class="px-4 py-2 border">Status</th>
        </tr>
    </thead>
    <tbody id="upload-status-body" class="text-gray-600 text-sm">
        <!-- Dynamically injected rows -->
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
                                        tr.classList.add('transition', 'duration-300', 'hover:bg-gray-50');
                                        let statusColor = 'text-yellow-600'; // default: pending
                                        if (row.status === 'Completed') statusColor = 'text-green-600';
                                        else if (row.status === 'Failed') statusColor = 'text-red-600';
                                        else if (row.status === 'Processed') statusColor = 'text-blue-600';

                                        tr.innerHTML = `
                                            <td class="border px-4 py-2">
                                                ${row.created_at}<br><span class="text-xs text-gray-500">(${row.human_time})</span>
                                            </td>
                                            <td class="border px-4 py-2">${row.file_name}</td>
                                            <td class="border px-4 py-2 font-semibold ${statusColor}">${row.status}</td>
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