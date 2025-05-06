@extends ('layouts.app')

@section('content')

<h1>Upload CSV File</h1>

<form action="" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" required>
    <button type="submit">Upload</button>
</form>

<hr>
<h2>Uploaded Files</h2>
@if (isset($uploads) && $uploads->count())
<table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Time</th>
                <th>File Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="upload-status-body">
            @foreach($uploads as $upload)
                <tr>
                    <td>
                        {{ $upload->created_at->format('Y-m-d H:i:s') }}
                        <br>
                        ({{ $upload->created_at->diffForHumans() }})
                    </td>
                    <td>{{ $upload->file_name }}</td>
                    <td>{{ ucfirst($upload->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No files have been uploaded yet. </p>
@endif
    
@endsection