<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View as ViewInstance;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HtmlSanitizerInterface::class, function (): HtmlSanitizer {
            $config = (new HtmlSanitizerConfig)->allowLinkSchemes(['http', 'https'])->allowRelativeLinks(false)->withMaxInputLength(400000);
            foreach (['p', 'br', 'h2', 'h3', 'strong', 'em', 'ul', 'ol', 'li'] as $tag) {
                $config = $config->allowElement($tag);
            }
            $config = $config->allowElement('a', ['href']);
            foreach (['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'template'] as $tag) {
                $config = $config->dropElement($tag);
            }

            return new HtmlSanitizer($config);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn () => Password::min(12)->letters()->numbers());

        View::composer(['admin.login', 'components.admin.layout'], function (ViewInstance $view): void {
            $view->with('businessSettings', Setting::current());
        });
    }
}
