@extends('layouts.app')
@push('style')
    <style>
        #btncre {
            width: 100%;
            text-align: left !important;
            margin-bottom: 20px;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success" id="success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <div class="card-body">

                            <h1>Welcome, {{ Auth::user()->name }}</h1>
                            <button class="btn btn-primary" id="btncre" data-toggle="modal"
                                data-target="#createEventModal">Create
                                Event</button>

                            <div class="list-group" id="myList" role="tablist">
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('admin.eventindex') }}" role="tab"> Events List</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Create Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.events.store') }}" class="event-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Event Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        {{-- <div class="form-group">
                            <label for="year">Select Year</label>
                            <select id="year" name="year" class="form-control" required>

                                @for ($i = date('Y'); $i <= date('Y') + 20; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div> --}}

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                            <span id="start_date_error" class="error-message"></span>
                        </div>


                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                            <span id="end_date_error" class="error-message"></span>

                        </div>


                        <div class="form-group">
                            <label for="assigned_judges">Assigned Judges</label>
                            <select name="assigned_judges[]" id="assigned_judges" class="form-control" multiple required>
                                @foreach ($judges as $judge)
                                    <option value="{{ $judge->id }}">{{ $judge->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {


            $('#start_date, #end_date').on('input', function() {
                //alert("11");
                validateDateRange();
            });

            function validateDateRange() {
                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());
                var startError = $('#start_date_error');
                var endError = $('#end_date_error');

                if (startDate > endDate) {
                    startError.text('Start date cannot be after end date').css('color', 'red');
                    endError.text('End date cannot be before start date').css('color', 'red');

                } else {
                    startError.text('');
                    endError.text('');
                }
            }
            $(".event-form").submit(function(event) {
                var startDate = new Date($("#start_date").val());
                var endDate = new Date($("#end_date").val());
                var startError = $('#start_date_error');

                if (startDate > endDate) {
                    startError.text('Start date cannot be after end date').css('color', 'red');
                    event.preventDefault();
                }
            });

            setTimeout(function() {
                $("#success").fadeOut(100);
                $(".alert-danger").fadeOut(100);

            }, 3000)

        });
    </script>
@endpush
