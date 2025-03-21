@extends('layouts.app')
@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold">Property Listings</h1>
    @foreach ($properties as $property)
        <div class="bg-white p-4 shadow rounded-lg my-4">
            <h2 class="text-xl font-semibold">{{ $property->title }}</h2>
            <p>{{ $property->description }}</p>
            <p><strong>Price:</strong> ${{ $property->price }}</p>
            <p><strong>Location:</strong> {{ $property->location }}</p>
            @if ($property->image)
                <img src="{{ asset('storage/' . $property->image) }}" class="w-48 h-32 object-cover">
            @endif
        </div>
    @endforeach
</div>
@endsection