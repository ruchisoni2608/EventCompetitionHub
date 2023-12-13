@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Final Rank</h2>
                <table id="myTable" class="table table-bordered display" cellspacing="0" width="100%">
                    <thead class="bg-table">
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Total Score</th>
                            <th>Date of Birth</th>
                            {{-- <th>Competition Date</th> --}}
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($topWinners->isEmpty())
                            <tr>
                                <td colspan="5">
                                    <h5>Not Allocate any Ranking.</h5>
                                </td>
                            </tr>
                        @else
                            @foreach ($topWinners as $index => $winner)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $winner->name }}</td>
                                    <td>{{ $winner->total_score }}</td>
                                    <td>{{ Carbon\Carbon::parse($winner->dob)->format('d M,Y') }}</td>
                                    {{-- <td>{{ $winner->created_at }}</td> --}}
                                    <td>
                                        <span style='font-size:31px;'>&#127881; &#127873;</span>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            //  $('#myTable').DataTable();
            @if ($topWinners->isNotEmpty())
                var table = $('#myTable').DataTable();
            @endif
        });
    </script>
@endpush
