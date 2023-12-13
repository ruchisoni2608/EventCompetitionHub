@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <h2>Participate index </h2>
                    </div>

                </div>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th width="280px">Action</th>
                </tr>
                @php $i=0; @endphp
                @foreach ($products as $product)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td><img src="/image/{{ $product->image }}" width="100px"></td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->costume }}</td>
                        <td>{{ $product->skill }}</td>
                        <td>{{ $product->punctual }}</td>
                        <td>
                            <form action="{{ route('judge.destroy', $product->id) }}" method="POST">

                                <a class="btn btn-info" href="{{ route('judge.show', $product->id) }}">Show</a>

                                <a class="btn btn-primary" href="{{ route('judge.edit', $product->id) }}">Edit</a>

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
