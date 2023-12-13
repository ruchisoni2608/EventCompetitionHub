@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Participate View </h2>
                    </div>

                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success" id="success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger" id="error">
                    <p>{{ $message }}</p>
                </div>
            @endif



            <table id="myTable" class="table table-bordered display" cellspacing="0" width="100%">
                <thead class="bg-table">
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Name</th>
                        {{-- <th>Address</th> --}}
                        <th>Phone</th>
                        <th>Assign Ranking </th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>

                @php $i=0; @endphp
                @foreach ($participate as $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td><img src="/image/{{ $value->image }}" width="100px" height="100px"></td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->phone }}</td>

                        <td>

                            @if ($isrank->contains($value->id))
                                @if (isset($rankingNames[$value->id]))
                                    {{ $rankingNames[$value->id] }}
                                @endif
                            @else
                                No Ranking Assign
                            @endif
                        </td>

                        <td>
                            @php
                                $isDateInRange = 0;
                                $currentDate = \Carbon\Carbon::now()->toDateString();
                                $selectedEventStartDate = \Carbon\Carbon::parse(session('selectedEventStartDate'))->toDateString();
                                $selectedEventEndDate = \Carbon\Carbon::parse(session('selectedEventEndDate'))->toDateString();

                                if ($currentDate >= $selectedEventStartDate && $currentDate <= $selectedEventEndDate) {
                                    $isDateInRange = 1;
                                }
                                //dd($currentDate, $selectedEventStartDate);
                            @endphp


                            {{-- @php
                                $isDateInRange = 0;
                                $currentDate = \Carbon\Carbon::now();
                                $selectedEventStartDate = \Carbon\Carbon::parse(session('selectedEventStartDate'));
                                $selectedEventEndDate = \Carbon\Carbon::parse(session('selectedEventEndDate'));
                                if ($currentDate->between($selectedEventStartDate, $selectedEventEndDate, true)) {
                                    $isDateInRange = 1;
                                }
                            @endphp
                            @dd($currentDate, $selectedEventStartDate, $selectedEventEndDate); --}}
                            @if ($isDateInRange == 1)
                                {{-- <form action="{{ route('judge.destroy', $value->id) }}" method="POST">

                                    <a class="btn btn-info" href="{{ route('judge.show', $value->id) }}">Show</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $value->id }})">Delete</button>

                                </form> --}}
                                <form action="{{ route('judge.destroy', $value->id) }}" method="POST">
                                    <a class="btn btn-info"
                                        href="{{ route('judge.show', ['resource' => $value->hashId]) }}">Show</a>

                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $value->id }})">Delete</button>
                                </form>
                            @else
                                <form action="#" method="POST">
                                    <button class="btn btn-secondary" disabled>Show</button>
                                    <button type="button" class="btn btn-danger" disabled>Delete</button>

                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </table>
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

            $('#myTable').DataTable();
            setTimeout(function() {
                $("#success").fadeOut(1500);
                $("#error").fadeOut(1500);
            }, 5000)

        });

        function confirmDelete(id) {

            if (confirm("Are you sure you want to delete this item?")) {

                document.querySelector('form[action="{{ route('judge.destroy', '') }}/' + id + '"]').submit();
            }
        }
    </script>
@endpush
