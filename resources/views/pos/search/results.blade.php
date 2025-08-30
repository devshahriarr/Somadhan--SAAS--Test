@extends('master')
@section('title', '| Services Name')
@section('admin')

<style>
    .highlighted {
        background-color: #e5e7eb;
    }
    #search-results-table tbody tr {
        cursor: pointer;
    }
</style>


    <h1 class="text-2xl mb-4">Search Results for "{{ $query }}"</h1>
    @if ($results->isEmpty())
        <p>No results found.</p>
    @else
        <ul class="list-disc pl-5">
            @foreach ($results as $product)
            {{-- @dd($product); --}}
            {{-- @dd($product->category_id); --}}
                <li>
                    <strong>{{ $product->id }}</strong><br>
                    <strong>{{ $product->name }}</strong><br>
                    <strong>{{ $product->description }}</strong>
                    <br>
                    <strong>cat_id :{{ $product->category_id ?? '0'}}</strong>
                    <br>
                    <strong>unit :{{ $product->unit ?? ''}}</strong><br>
                    <strong>{{ $product->status ?? ''}}</strong>
                    <ul class="ml-4">
                        @foreach ($product->variations as $variation)
                            {{-- @dd($variation); --}}
                            <li>
                                Variant ID: {{ $variation->variation_id ?? 'N/A' }} |
                                Variant: {{ $variation->variation_name ?? 'N/A' }} |
                                Size: {{ $variation->size ?? 'N/A' }} |
                                Color: {{ $variation->color ?? 'N/A' }} |
                                {{-- product id: {{ $variation->product_id  ?? 0 }} --}}
                                Barcode: {{ $variation->barcode ?? 0 }}
                                Cost Price: {{ $variation->cost_price ?? 0 }}
                                b2b_price: {{ $variation->b2b_price ?? 0 }}
                                b2c price: ${{ $variation->b2c_price ?? 0 }} |
                                {{-- size id: {{ $variation->size_id ?? 0 }} --}}
                                Stock Qty: {{ $variation->stock_quantity ?? 0 }}
                                status: {{ $variation->status ?? 0 }}
                    </li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ url('/') }}" class="mt-4 inline-block text-blue-600">Back to Home</a>

@endsection
