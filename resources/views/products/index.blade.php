@extends('layouts.app')

@section('content')
<h1>Catalogue de produits</h1>

<form method="GET" action="{{ route('products.index') }}">
    <input type="text" name="q" placeholder="Recherche..." value="{{ request('q') }}">
    <select name="category">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @if(request('category') == $cat->id) selected @endif>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <button type="submit">Filtrer</button>
</form>

<div class="row">
    @foreach($products as $product)
        <div class="col-3">
            <h3>
                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
            </h3>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" width="150">
            @endif
            <p>{{ $product->price }} €</p>
            <p>{{ $product->stock > 0 ? 'En stock' : 'Rupture' }}</p>
        </div>
    @endforeach
</div>

{{ $products->withQueryString()->links() }}
@endsection