@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Events</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success" id="success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <table id="myTable" class="table table-bordered display" cellspacing="0" width="100%">
            <thead class="bg-table">

                <tr>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Assigned Judges</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ Carbon\Carbon::parse($event->start_date)->format('d M,Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($event->end_date)->format('d M,Y') }}</td>

                        <td>
                            @php
                                $assignedJudges = json_decode($event->assigned_judges);
                            @endphp
                            @if (!empty($assignedJudges))
                                <ul>
                                    @foreach ($assignedJudges as $judgeId)
                                        <?php $judge = \App\Models\User::find($judgeId); ?>
                                        <li>{{ $judge ? $judge->name : 'Unknown Judge' }}</li>
                                    @endforeach
                                </ul>
                            @else
                                Unknown Judge
                            @endif
                        </td>

                        <td>

                            <a href="#" class="btn btn-primary edit-event" data-toggle="modal"
                                data-target="#editEventModal{{ $event->id }}">Edit</a>

                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Event Modal -->
                    <div class="modal fade" id="editEventModal{{ $event->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editEventModalLabel{{ $event->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editEventModalLabel{{ $event->id }}">Edit Event</h5>


                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('admin.events.update', $event) }}"
                                        class="edit-event-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name">Event Name</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                required value="{{ $event->name }}">
                                        </div>



                                        {{-- <div class="form-group">
                                            <label for="year">Select Year</label>

                                            <select id="year" name="year" class="form-control" required>
                                                @for ($i = date('Y'); $i <= date('Y') + 20; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $event->year == $i ? 'selected' : '' }}>{{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div> --}}

                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                required value="{{ $event->start_date }}">

                                            <span id="start_date_error" class="error-message"></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                required value="{{ $event->end_date }}">
                                            <span id="end_date_error" class="error-message"></span>
                                        </div>

                                        <div class="form-group">
                                            <label for="assigned_judges">Assigned Judges</label>
                                            <select name="assigned_judges[]" id="assigned_judges" class="form-control"
                                                multiple required>
                                                @foreach ($judges as $judge)
                                                    <option value="{{ $judge->id }}"
                                                        {{ in_array($judge->id, json_decode($event->assigned_judges)) ? 'selected' : '' }}>
                                                        {{ $judge->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
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

            $('#myTable').DataTable({
                "order": [
                    [1, 'asc']
                ]
            });
            $(".edit-event").click(function() {
                //alert("2");
                var modalId = $(this).data("target");
                var modal = $(modalId);


                var startDateInput = modal.find("#start_date");
                var endDateInput = modal.find("#end_date");
                var startError = modal.find("#start_date_error");
                var endError = modal.find("#end_date_error");



                startDateInput.on("change", function() {
                    // alert("4");
                    validateDateRange();
                });

                endDateInput.on("change", function() {
                    validateDateRange();
                });

                function validateDateRange() {
                    var startDate = new Date(startDateInput.val());
                    var endDate = new Date(endDateInput.val());


                    if (startDate > endDate) {
                        var errorMessage = 'Start date cannot be after end date';
                        startError.text(errorMessage);
                        startError.css('color', 'red');
                        endError.text(errorMessage);
                        endError.css('color', 'red');
                    } else {
                        startError.text('');
                        endError.text('');
                    }
                }

            });
            $(".edit-event-form").submit(function(event) {
                var modal = $(this).closest(".modal");
                var startDate = new Date($("#start_date", modal).val());
                var endDate = new Date($("#end_date", modal).val());
                var startError = $("#start_date_error", modal);

                if (startDate > endDate) {
                    var errorMessage = 'Start date cannot be after end date';
                    startError.text(errorMessage);
                    startError.css('color', 'red');
                    event.preventDefault();
                }
            });

            setTimeout(function() {
                $("#success").fadeOut(1500);
            }, 5000)
        });
    </script>
@endpush
