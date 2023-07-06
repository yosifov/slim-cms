@extends("layouts.master", [
    'title' => $title ?? ''
])

@section("content")

<h1>
    {{ trans('home.heading', $locale ?? "bg") }}
</h1>

@endsection