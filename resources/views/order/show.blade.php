@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détail de la commande #{{ $order->order_number ?? $order->id }}</h1>
    <p>Date : {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p>Statut : <strong>{{ ucfirst($order->status) }}</strong></p>
    <p>Total : <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong></p>
    <hr>
    <h4>Produits commandés :</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                <td>{{ number_format($item->total, 2, ',', ' ') }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    <h4>Adresse de livraison :</h4>
    <p>{{ $order->shipping_address }}</p>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Retour à mes commandes</a>
</div>
@endsection