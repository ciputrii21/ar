@extends('layouts.mainlayout')

@section('title', 'Dashboard')

@section('page-name', 'dashboard')

@section('content')
    <h1>Arsip List</h1>

    <div class="my-5 d-flex justify-content-end">
        <a href="arsip-deleted" class="btn btn-secondary me-3">View Deleted Data</a>
        <a href="arsip-add" class="btn btn-primary">Add Data</a>
    </div>

    <div class="mt-5">
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
    </div>

    <div class="my-5">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($arsips as $item)
                    <tr>
                        <td>{{ $loop->iteration }} </td>
                        <td>{{ $item->arsip_code }} </td>
                        <td>{{ $item->title }} </td>
                        <td>
                            @foreach ($item->categories as $category)
                                {{ $category->name }} <br>
                            @endforeach
                        </td>
                        <td>{{ $item->status }} </td>
                        <td>
                            <a href="/arsip-edit/{{$item->slug}}">edit</a>
                            <a href="/arsip-delete/{{$item->slug}}">delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection