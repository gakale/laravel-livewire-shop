<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FixComponentViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:fix-component-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour tous les composants Livewire pour utiliser le trait FindsAlternativeViews';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $componentsDir = dirname(__DIR__, 2) . '/Components';
        $componentsPath = realpath($componentsDir);
        
        if (!$componentsPath) {
            $this->error("Le répertoire des composants n'a pas été trouvé");
            return Command::FAILURE;
        }
        
        $this->info("Recherche des composants Livewire dans {$componentsPath}");
        
        $files = File::files($componentsPath);
        $componentsCount = 0;
        $updatedCount = 0;
        
        foreach ($files as $file) {
            // Ignorer le répertoire Traits
            if (Str::contains($file->getPathname(), 'Traits')) {
                continue;
            }
            
            $componentsCount++;
            $this->line("Traitement de {$file->getFilename()}...");
            
            $content = File::get($file->getPathname());
            
            // Vérifier si le fichier utilise déjà le trait
            if (Str::contains($content, 'FindsAlternativeViews')) {
                $this->info("  → Déjà mis à jour");
                continue;
            }
            
            // 1. Ajouter l'import du trait
            $content = preg_replace(
                '/namespace LaravelLivewireShop\\\\LaravelLivewireShop\\\\Components;\\s+use Livewire\\\\Component;/',
                "namespace LaravelLivewireShop\\\\LaravelLivewireShop\\\\Components;\\n\\nuse Livewire\\\\Component;\\nuse LaravelLivewireShop\\\\LaravelLivewireShop\\\\Components\\\\Traits\\\\FindsAlternativeViews;",
                $content
            );
            
            // 2. Ajouter l'utilisation du trait dans la classe
            $content = preg_replace(
                '/class ([a-zA-Z0-9_]+) extends Component\\s+{/',
                "class $1 extends Component\\n{\\n    use FindsAlternativeViews;",
                $content
            );
            
            // 3. Modifier la méthode render pour utiliser findViewPath
            if (preg_match('/public function render\(\).*?{.*?return view\([\'"](livewire-shop::components\.[a-zA-Z0-9_-]+)[\'"]/', $content, $matches)) {
                $viewName = str_replace('livewire-shop::components.', '', $matches[1]);
                $content = preg_replace(
                    '/return view\([\'"]livewire-shop::components\.[a-zA-Z0-9_-]+[\'"]/',
                    "\$viewPath = \$this->findViewPath('$viewName');\\n        return view(\$viewPath",
                    $content
                );
                
                $updatedCount++;
                $this->info("  → Mis à jour avec succès");
            } else {
                $this->warn("  → Pas de méthode render compatible trouvée");
            }
            
            // Sauvegarder les modifications
            File::put($file->getPathname(), $content);
        }
        
        $this->newLine();
        $this->info("Mise à jour terminée: {$updatedCount}/{$componentsCount} composants mis à jour");
        
        // Enregistrer la commande dans le service provider
        $this->info("N'oubliez pas d'enregistrer cette commande dans ShopServiceProvider");
        
        return Command::SUCCESS;
    }
}
