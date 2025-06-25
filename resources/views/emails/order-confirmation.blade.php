<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de commande</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 30px; }
        .order-summary { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .item { display: flex; justify-content: between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .total { font-weight: bold; font-size: 1.2em; }
        .footer { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏪 Ma Boutique</h1>
            <h2>Confirmation de commande</h2>
        </div>
        
        <div class="content">
            <h3>Bonjour {{ $order->billing_address['first_name'] }},</h3>
            
            <p>Merci pour votre commande ! Nous avons bien reçu votre commande <strong>{{ $order->order_number }}</strong> et nous la préparons avec soin.</p>
            
            <div class="order-summary">
                <h4>📋 Récapitulatif de votre commande</h4>
                
                @foreach($order->items as $item)
                    <div class="item">
                        <div style="flex: 1;">
                            <strong>{{ $item->product_snapshot['name'] }}</strong><br>
                            <small>Quantité: {{ $item->quantity }} × {{ number_format($item->price, 2) }}€</small>
                        </div>
                        <div>
                            <strong>{{ number_format($item->total, 2) }}€</strong>
                        </div>
                    </div>
                @endforeach
                
                <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #007bff;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Sous-total:</span>
                        <span>{{ number_format($order->subtotal, 2) }}€</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div style="display: flex; justify-content: space-between; color: green;">
                            <span>Réduction:</span>
                            <span>-{{ number_format($order->discount_amount, 2) }}€</span>
                        </div>
                    @endif
                    
                    @if($order->tax_amount > 0)
                        <div style="display: flex; justify-content: space-between;">
                            <span>TVA:</span>
                            <span>{{ number_format($order->tax_amount, 2) }}€</span>
                        </div>
                    @endif
                    
                    @if($order->shipping_amount > 0)
                        <div style="display: flex; justify-content: space-between;">
                            <span>Livraison:</span>
                            <span>{{ number_format($order->shipping_amount, 2) }}€</span>
                        </div>
                    @endif
                    
                    <div style="display: flex; justify-content: space-between;" class="total">
                        <span>Total:</span>
                        <span>{{ number_format($order->total, 2) }}€</span>
                    </div>
                </div>
            </div>
            
            <h4>📍 Adresse de livraison</h4>
            <address>
                {{ $order->shipping_address['first_name'] ?? $order->billing_address['first_name'] }} 
                {{ $order->shipping_address['last_name'] ?? $order->billing_address['last_name'] }}<br>
                {{ $order->shipping_address['address'] ?? $order->billing_address['address'] }}<br>
                {{ $order->shipping_address['postal_code'] ?? $order->billing_address['postal_code'] }} 
                {{ $order->shipping_address['city'] ?? $order->billing_address['city'] }}
            </address>
            
            <p><strong>Que se passe-t-il maintenant ?</strong></p>
            <ul>
                <li>Votre commande sera traitée sous 24h ouvrées</li>
                <li>Vous recevrez un email avec le numéro de suivi</li>
                <li>Livraison estimée : 2-3 jours ouvrés</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Questions ? Contactez-nous à <a href="mailto:contact@ma-boutique.com">contact@ma-boutique.com</a></p>
            <p>© 2024 Ma Boutique - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
