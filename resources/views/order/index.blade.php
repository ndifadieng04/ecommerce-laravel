@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes commandes</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number ?? $order->id }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm">Voir détail</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Aucune commande trouvée.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    {{ $orders->links() }}
</div>
@endsection