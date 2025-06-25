<?php

namespace VotreNamespace\LaravelLivewireShop\Console\Commands;

use Illuminate\Console\Command;
use VotreNamespace\LaravelLivewireShop\Models\Product;

class CreateProductCommand extends Command
{
    protected $signature = 'shop:create-products';
    protected $description = 'Créer des produits d\'exemple';

    public function handle()
    {
        $this->info('🛍️ Création de produits d\'exemple...');

        $products = [
            [
                'name' => 'T-shirt Premium',
                'description' => 'T-shirt en coton bio de haute qualité, confortable et durable.',
                'price' => 29.99,
                'stock' => 50,
                'attributes' => ['couleurs' => 'Noir, Blanc, Bleu', 'tailles' => 'S, M, L, XL']
            ],
            [
                'name' => 'Casque Audio Bluetooth',
                'description' => 'Casque sans fil avec réduction de bruit active et autonomie 30h.',
                'price' => 149.99,
                'stock' => 25,
                'attributes' => ['couleurs' => 'Noir, Blanc']
            ],
            [
                'name' => 'Sac à Dos Urbain',
                'description' => 'Sac à dos moderne avec compartiment laptop et ports USB.',
                'price' => 79.99,
                'stock' => 30,
                'attributes' => ['couleurs' => 'Noir, Gris, Marine']
            ],
            [
                'name' => 'Montre Connectée',
                'description' => 'Montre intelligente avec GPS, cardio et étanchéité IP68.',
                'price' => 199.99,
                'stock' => 15,
                'attributes' => ['couleurs' => 'Noir, Rose, Argent']
            ],
            [
                'name' => 'Livre de Cuisine',
                'description' => 'Recettes faciles et savoureuses pour tous les jours.',
                'price' => 24.99,
                'stock' => 100,
                'attributes' => []
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
            $this->line("✅ Produit créé : {$productData['name']}");
        }

        $this->info('🎉 Produits d\'exemple créés avec succès !');
    }
}
