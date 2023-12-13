@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">


                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="card-body">

                            <h1>Welcome, {{ Auth::user()->name }}</h1>
                            <div class="list-group" id="myList" role="tablist">
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('participateindex') }}" role="tab">Paricipate index</a>
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('participate.index') }}" role="tab">Ranking index</a>
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('showDailyRanking') }}" role="tab">Today's Rank</a>
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('AllDaysRank') }}" role="tab">All Day's Rank</a>
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('finalRanking') }}" role="tab">Final Rank</a>
                                <a class="list-group-item list-group-item-action active" data-toggle="list"
                                    href="{{ route('judge.index') }}" role="tab">Allocate Rank</a>



                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
