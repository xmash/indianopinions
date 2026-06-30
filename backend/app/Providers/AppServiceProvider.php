<?php

namespace App\Providers;

use App\Enums\Permission;
use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Post::class, PostPolicy::class);

        foreach (Permission::cases() as $permission) {
            Gate::define($permission->value, fn ($user) => $user->hasPermission($permission->value));
        }

        \Illuminate\Support\Facades\Route::bind('post', function (string $value) {
            $query = Post::query();

            if (ctype_digit($value)) {
                $query->whereKey((int) $value);
            } else {
                $query->where('slug', $value);
            }

            return $query->firstOrFail();
        });

        $publicUrl = config('app.url');

        if (
            ! $this->app->runningInConsole()
            && is_string($publicUrl)
            && $publicUrl !== ''
            && ! str_contains($publicUrl, 'localhost')
            && ! str_contains($publicUrl, '127.0.0.1')
        ) {
            URL::forceScheme('https');

            $request = request();
            $forwardedHost = $request->header('X-Forwarded-Host');
            $host = is_string($forwardedHost) && $forwardedHost !== ''
                ? strtolower(trim(explode(',', $forwardedHost)[0]))
                : null;

            $allowedHosts = config('app.allowed_hosts', []);

            if ($host !== null && in_array($host, $allowedHosts, true)) {
                URL::forceRootUrl('https://'.$host);
            } else {
                URL::forceRootUrl($publicUrl);
            }
        }
    }
}
