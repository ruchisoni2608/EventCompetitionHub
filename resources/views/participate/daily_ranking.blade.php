@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Today's Rank</h2>
                <table id="myTable" class="table table-bordered display" cellspacing="0" width="100%">
                    <thead class="bg-table">

                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Total Score</th>
                            <th>Date of Birth</th>
                            <th>Prize</th>
                        </tr>
                    </thead>

                    @if (isset($noEventMessage))
                        <p>{{ $noEventMessage }}</p>
                    @else
                        @if ($rankedParticipants->isEmpty())
                            <tr>
                                <td colspan="5">
                                    <h5>Not Allocate any Ranking Today.</h5>
                                </td>
                            </tr>
                        @else
                            @foreach ($rankedParticipants->take(3) as $index => $participant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $participant->name }}</td>
                                    <td>{{ $participant->total_score }}</td>

                                    <td>{{ Carbon\Carbon::parse($participant->dob)->format('d M,Y') }}</td>
                                    <td>
                                        <span style='font-size:31px;'>&#127881; &#127873;</span>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    @endif
                </table>
                <div>
                    <h2>All Today's Ranking</h2>
                    <table id="myTable1" class="table table-bordered display" cellspacing="0" width="100%">
                        <thead class="bg-table">
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Total Score</th>
                                <th>Date of Birth</th>
                            </tr>
                        </thead>

                        @if ($rankedParticipants->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <h5>Not allocated any ranking today.</h5>
                                </td>
                            </tr>
                        @else
                            @foreach ($rankedParticipants as $index => $participant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $participant->name }}</td>
                                    <td>{{ $participant->total_score }}</td>
                                    <td>{{ Carbon\Carbon::parse($participant->dob)->format('d M,Y') }}</td>

                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            @if ($rankedParticipants->isNotEmpty())
                var table = $('#myTable').DataTable();
                var table1 = $('#myTable1').DataTable();
            @endif

        });
    </script>
@endpush
