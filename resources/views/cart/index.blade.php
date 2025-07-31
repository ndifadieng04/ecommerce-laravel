@extends('layouts.app')

@section('content')
    <h1>Mon panier</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(empty($cart))
        <p>Votre panier est vide.</p>
    @else
        <table class="table">
            <th>Action</th>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price'] }} €</td>
                        <td>
    <form action="{{ route('cart.update', $item['id']) }}" method="POST" style="display:inline-flex;">
        @csrf
        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px;">
        <button type="submit" class="btn btn-primary btn-sm ms-1">OK</button>
    </form>
</td>
                        <td>{{ $item['price'] * $item['quantity'] }} €</td>
                    </tr>
                @endforeach
            </tbody>
            <td>
    <form action="{{ route('cart.remove', $item['id']) }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
    </form>
</td>
        </table>
       <h4 class="mt-3">Total à payer : <span class="text-success">{{ $total }} €</span></h4>
       @if(!empty($cart))
    <a href="{{ route('order.checkout') }}" class="btn btn-success mt-3">Passer la commande</a>
@endif
    @endif
@endsection