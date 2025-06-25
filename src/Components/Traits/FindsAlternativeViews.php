<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Components\Traits;

trait FindsAlternativeViews
{
    /**
     * Permet de trouver les vues dans différents emplacements possibles
     * en fonction de si elles ont été publiées ou non
     * 
     * @param string $baseView Le nom de base de la vue (sans préfixe)
     * @return string Le chemin complet vers la vue
     */
    protected function findViewPath($baseView)
    {
        $possiblePaths = [
            // 1. Vue publiée localement (création utilisateur)
            "livewire.{$baseView}",
            
            // 2. Vue publiée dans vendor (publication standard)
            "vendor.livewire-shop.components.{$baseView}",
            
            // 3. Vue originale du package (par défaut)
            "livewire-shop::components.{$baseView}"
        ];
        
        foreach ($possiblePaths as $path) {
            if (view()->exists($path)) {
                return $path;
            }
        }
        
        // Si aucune vue n'est trouvée, retourner le chemin par défaut
        return "livewire-shop::components.{$baseView}";
    }
}
