<?php

namespace LaravelLivewireShop\LaravelLivewireShop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishShopViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shop:publish-views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publie les vues du package avec les modèles et les contrôleurs nécessaires';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Publier les vues
        $this->info('Publication des vues du shop...');
        $this->call('vendor:publish', [
            '--tag' => 'livewire-shop-views',
            '--force' => true
        ]);
        
        // S'assurer que le répertoire des contrôleurs existe
        $controllersPath = app_path('Http/Controllers/Shop');
        if (!File::exists($controllersPath)) {
            File::makeDirectory($controllersPath, 0755, true);
        }
        
        // Publier le contrôleur ShopController dans l'application utilisateur
        $this->info('Création du contrôleur ShopController dans votre application...');
        $shopControllerContent = <<<'EOT'
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use LaravelLivewireShop\LaravelLivewireShop\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->latest()
            ->paginate(12);

        return view('vendor.livewire-shop.pages.shop', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('vendor.livewire-shop.pages.product', compact('product'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(12);
            
        return view('vendor.livewire-shop.pages.search', compact('products', 'query'));
    }
}
EOT;
        
        File::put(app_path('Http/Controllers/Shop/ShopController.php'), $shopControllerContent);
        
        // Ajouter les routes nécessaires
        $this->info('Création du fichier de routes shop.php dans votre application...');
        $shopRoutesContent = <<<'EOT'
<?php

use App\Http\Controllers\Shop\ShopController;

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/product/{slug}', [ShopController::class, 'show'])->name('shop.product.show');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');

EOT;
        
        if (!File::exists(base_path('routes/shop.php'))) {
            File::put(base_path('routes/shop.php'), $shopRoutesContent);
            
            // Ajouter le chargement des routes dans RouteServiceProvider
            $this->info('Assurez-vous d\'inclure routes/shop.php dans votre RouteServiceProvider :');
            $this->line('$this->routes(function () {');
            $this->line('    // Autres routes...');
            $this->line('    Route::middleware(\'web\')->group(base_path(\'routes/shop.php\'));');
            $this->line('});');
        }
        
        $this->info('Les vues, contrôleurs et routes du shop ont été publiés avec succès !');
        $this->info('Les routes du shop sont maintenant accessibles.');
        return Command::SUCCESS;
    }
}
