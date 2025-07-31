@extends('layouts.app')

@section('content')
    <h1>Passer la commande</h1>

    <h4>Récapitulatif du panier :</h4>
    <ul>
        @php $total = 0; @endphp
        @foreach($cart as $item)
            @php $total += $item['price'] * $item['quantity']; @endphp
            <li>
                {{ $item['name'] }} x {{ $item['quantity'] }} = {{ $item['price'] * $item['quantity'] }} €
            </li>
        @endforeach
    </ul>
    <h5>Total : {{ $total }} €</h5>

    <form method="POST" action="{{ route('order.process') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom complet</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Adresse de livraison</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="payment" class="form-label">Mode de paiement</label>
            <select name="payment" id="payment" class="form-control" required>
                <option value="online">Paiement en ligne (simulé)</option>
                <option value="cod">Paiement à la livraison</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Valider la commande</button>
    </form>
@endsection