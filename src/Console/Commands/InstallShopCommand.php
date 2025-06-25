<?php

namespace VotreNamespace\LaravelLivewireShop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallShopCommand extends Command
{
    protected $signature = 'shop:install {--force : Écraser les fichiers existants}';
    protected $description = 'Installer le plugin Laravel Livewire Shop';

    public function handle()
    {
        $this->info('🚀 Installation du plugin Laravel Livewire Shop...');

        // Publier la configuration
        $this->call('vendor:publish', [
            '--tag' => 'livewire-shop-config',
            '--force' => $this->option('force')
        ]);

        // Publier les vues
        $this->call('vendor:publish', [
            '--tag' => 'livewire-shop-views',
            '--force' => $this->option('force')
        ]);

        // Publier les migrations
        $this->call('vendor:publish', [
            '--tag' => 'livewire-shop-migrations',
            '--force' => $this->option('force')
        ]);

        // Exécuter les migrations
        if ($this->confirm('Voulez-vous exécuter les migrations maintenant ?', true)) {
            $this->call('migrate');
        }

        // Créer des produits d'exemple
        if ($this->confirm('Voulez-vous créer des produits d\'exemple ?', true)) {
            $this->call('shop:create-products');
        }

        $this->info('✅ Installation terminée !');
        $this->line('');
        $this->line('📚 Prochaines étapes :');
        $this->line('1. Configurez votre boutique dans config/livewire-shop.php');
        $this->line('2. Ajoutez les routes dans votre fichier routes/web.php');
        $this->line('3. Visitez /boutique pour voir votre boutique');
        $this->line('');
        $this->line('📖 Documentation complète : https://github.com/votre-repo/laravel-livewire-shop');
    }
}
