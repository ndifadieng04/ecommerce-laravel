<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        
        .clear {
            clear: both;
        }
        
        .customer-info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .customer-info h3 {
            margin: 0 0 10px 0;
            color: #667eea;
            font-size: 14px;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .products-table th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        .products-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .products-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .totals {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        
        .total-row.final {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #667eea;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 18px;
            font-weight: bold;
            color: #667eea;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #ffc107; color: #000; }
        .status-shipped { background-color: #17a2b8; color: white; }
        .status-delivered { background-color: #28a745; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <div class="logo">E-COMMERCE</div>
        <div>Votre boutique en ligne de confiance</div>
    </div>
    
    <!-- Informations de l'entreprise et de la facture -->
    <div class="company-info">
        <strong>E-COMMERCE</strong><br>
        123 Rue du Commerce<br>
        75001 Paris, France<br>
        Tél: +33 1 23 45 67 89<br>
        Email: contact@ecommerce.com<br>
        SIRET: 123 456 789 00012
    </div>
    
    <div class="invoice-info">
        <div class="invoice-number">FACTURE {{ $order->order_number }}</div>
        <div>Date: {{ $order->created_at->format('d/m/Y') }}</div>
        <div>Heure: {{ $order->created_at->format('H:i') }}</div>
        <div>Statut: 
            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        @if($order->payment_method)
        <div>Paiement: {{ ucfirst($order->payment_method) }}</div>
        @endif
    </div>
    
    <div class="clear"></div>
    
    <!-- Informations client -->
    <div class="customer-info">
        <h3>Informations client</h3>
        @if($order->user)
        <strong>{{ $order->user->name }}</strong><br>
        Email: {{ $order->user->email }}<br>
        @else
        <strong>{{ $order->name }}</strong><br>
        Email: {{ $order->email }}<br>
        Téléphone: {{ $order->phone }}<br>
        @endif
        <br>
        <strong>Adresse de livraison:</strong><br>
        {{ $order->shipping_address }}
    </div>
    
    <!-- Tableau des produits -->
    <table class="products-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->product)
                    <br><small>{{ $item->product->description }}</small>
                    @endif
                </td>
                <td>{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                <td>{{ $item->quantity }}</td>
                <td><strong>{{ number_format($item->total_price, 2, ',', ' ') }} €</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Totaux -->
    <div class="totals">
        <div class="total-row">
            <span>Sous-total:</span>
            <span>{{ number_format($order->items->sum('total_price'), 2, ',', ' ') }} €</span>
        </div>
        
        @if($order->shipping_cost > 0)
        <div class="total-row">
            <span>Frais de livraison:</span>
            <span>{{ number_format($order->shipping_cost, 2, ',', ' ') }} €</span>
        </div>
        @else
        <div class="total-row">
            <span>Frais de livraison:</span>
            <span>Gratuit</span>
        </div>
        @endif
        
        @if($order->tax_amount > 0)
        <div class="total-row">
            <span>TVA (20%):</span>
            <span>{{ number_format($order->tax_amount, 2, ',', ' ') }} €</span>
        </div>
        @endif
        
        <div class="total-row final">
            <span>TOTAL TTC:</span>
            <span>{{ number_format($order->total_amount, 2, ',', ' ') }} €</span>
        </div>
    </div>
    
    <div class="clear"></div>
    
    <!-- Notes -->
    @if($order->notes)
    <div style="margin-top: 30px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
        <strong>Notes:</strong><br>
        {{ $order->notes }}
    </div>
    @endif
    
    <!-- Informations de paiement -->
    <div style="margin-top: 20px; padding: 15px; background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px;">
        <strong>Informations de paiement:</strong><br>
        @if($order->payment_status == 'paid')
            ✅ Paiement reçu le {{ $order->paid_at ? $order->paid_at->format('d/m/Y à H:i') : 'N/A' }}
        @else
            ⏳ Paiement en attente
        @endif
    </div>
    
    <!-- Pied de page -->
    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p>Cette facture est générée automatiquement par notre système.</p>
        <p>Pour toute question, contactez-nous à contact@ecommerce.com</p>
        <p>Facture générée le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html> 