@extends('layouts.app')
@push('style')
    <style>
        .form-group {
            margin-bottom: 15px;
        }


        .container {
            margin-top: 15px;

        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2> Participate </h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('participate.index') }}"> Back</a>
                    </div>
                </div>
            </div>
            @if ($existingRanking)
                <div class="alert alert-success">
                    <p>Ranking is alredy done, You Can Edit it..</p>
                </div>
            @endif

            <div class="row">
                <div class="card" style="width:500px">
                    <div class="card-body">
                        <h6> <strong>Name: </strong> {{ $participates->name }}</h6>
                        <p><strong>Phone: </strong> {{ $participates->phone }}</p>
                        <p><strong>Image: </strong><img src="/image/{{ $participates->image }}" width="200px"
                                height="200px"></p>
                    </div>
                </div>
            </div>

            <div class="container">
                <form action="{{ route('participate.store') }}" method="POST">
                    @csrf
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <input type="hidden" name="userid" value="{{ $participates->id }}">
                            <input type="hidden" name="name" class="form-control" value="{{ $participates->name }}">
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <strong>Costume:</strong><br>
                                <label class="radio-inline">
                                    <input type="radio" name="costume" value="1"
                                        {{ $existingRanking && $existingRanking->costume === 1 ? 'checked' : '' }}>Very Bed

                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="radio-inline">
                                    <input type="radio" name="costume" value="2"
                                        {{ $existingRanking && $existingRanking->costume === 2 ? 'checked' : '' }}>Bed
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="costume" value="3"
                                        {{ $existingRanking && $existingRanking->costume === 3 ? 'checked' : '' }}> Average
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="costume" value="4"
                                        {{ $existingRanking && $existingRanking->costume === 4 ? 'checked' : '' }}>Good
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="costume" value="5"
                                        {{ $existingRanking && $existingRanking->costume === 5 ? 'checked' : '' }}>Very
                                    Good
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">

                            <div class="form-check form-check-inline">
                                <strong>Dance Skill:</strong><br>
                                <label class="radio-inline">
                                    <input type="radio" name="skill" value="1"
                                        {{ $existingRanking && $existingRanking->skill === 1 ? 'checked' : '' }}>Very Bed
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="skill" value="2"
                                        {{ $existingRanking && $existingRanking->skill === 2 ? 'checked' : '' }}>Bed
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="skill"value="3"
                                        {{ $existingRanking && $existingRanking->skill === 3 ? 'checked' : '' }}>Average
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="skill"value="4"
                                        {{ $existingRanking && $existingRanking->skill === 4 ? 'checked' : '' }}>Good
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="radio-inline">
                                    <input type="radio" name="skill"value="5"
                                        {{ $existingRanking && $existingRanking->skill === 5 ? 'checked' : '' }}>Very Good
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">

                            <div class="form-check form-check-inline">

                                <strong>Punctuality:</strong><br>
                                <label class="radio-inline">
                                    <input type="radio" name="punctual" value="1"
                                        {{ $existingRanking && $existingRanking->punctual === 1 ? 'checked' : '' }}>Too
                                    Late
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="punctual" value="2"
                                        {{ $existingRanking && $existingRanking->punctual === 2 ? 'checked' : '' }}>Late
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="punctual"value="3"
                                        {{ $existingRanking && $existingRanking->punctual === 3 ? 'checked' : '' }}>Average
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="punctual"value="4"
                                        {{ $existingRanking && $existingRanking->punctual === 4 ? 'checked' : '' }}>On Time
                                </label>
                            </div>
                            <div class="form-check form-check-inline">

                                <label class="radio-inline">
                                    <input type="radio" name="punctual"value="5"
                                        {{ $existingRanking && $existingRanking->punctual === 5 ? 'checked' : '' }}>Very
                                    Punctual
                                </label>
                            </div>
                        </div>
                    </div></br>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-left">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
            </div>

            </form>






        </div>
    </div>
@endsection
