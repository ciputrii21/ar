@extends('layouts.mainlayout')

@section('title', 'Delete Arsip')

@section('content')

<h2>Are you sure to delete arsip {{$arsip->title}} ?</h2>
<div class="mt-5">
    <a href="/arsip-destroy/{{$arsip->slug}} " class="btn btn-danger me-5">Sure</a>
    <a href="/arsips" class="btn btn-primary">Cancel</a>
</div>

@endsection