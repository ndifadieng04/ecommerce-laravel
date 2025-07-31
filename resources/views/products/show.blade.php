@extends('layouts.app')

@section('content')
    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">← Retour au catalogue</a>

    <div class="row">
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-3" alt="{{ $product->name }}">
            @endif
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <p><strong>Catégorie :</strong> {{ $product->category->name ?? 'Aucune' }}</p>
            <p>{{ $product->description }}</p>
            <p><strong>Prix :</strong> {{ $product->price }} €</p>
            <p>
                <strong>Disponibilité :</strong>
                @if($product->stock > 0)
                    <span class="text-success">En stock ({{ $product->stock }})</span>
                @else
                    <span class="text-danger">Rupture de stock</span>
                @endif
            </p>
            @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <label for="quantity">Quantité :</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                    <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                </form>
            @endif
        </div>
    </div>
@endsection