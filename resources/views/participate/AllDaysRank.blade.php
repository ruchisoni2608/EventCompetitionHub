@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>All Day's Rank</h2>

        <table class="table table-bordered display" cellspacing="0" width="100%">
            <thead class="bg-table">
                <tr>

                    <th>Rank</th>
                    <th>Name</th>
                    <th>Total Score</th>
                    <th>Date of Birth</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($top3Rank as $date => $rankings)
                    <tr>
                        <th colspan="4">Date: {{ $date }}</th>
                    </tr>
                    @foreach ($rankings as $index => $participant)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $participant->name }}</td>
                            <td>{{ $participant->total_score }}</td>
                            <td>{{ Carbon\Carbon::parse($participant->dob)->format('d M,Y') }}</td>

                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('script')
@endpush
