<?php

namespace App\Providers;

use App\Models\Block;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use PDOException;
use Request;
use Spatie\Enum\Laravel\Http\EnumRequest;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerRequestTransformMacro();
    }

    /**
     * @throws \ReflectionException
     */
    protected function registerRequestTransformMacro(): void
    {
        Request::mixin(new EnumRequest);
    }

    public function boot(): void
    {
        try {
            View::share('lastBlock', Block::latest()->first());
        } catch (PDOException $e) {}

        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }
                return $value;
            });
        });
    }
}
