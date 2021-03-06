<?php

namespace NathanBurkett\Ecosystem\Middleware;

use Closure;
use NathanBurkett\Ecosystem\Ecosystem;
use NathanBurkett\Ecosystem\Builders\HtmlBuilder;

class CheckEcosystemAction
{
    public function handle($request, Closure $next)
    {
        $route_actions = $request->route()->getAction();

        if (!empty($type = $route_actions['ecosystem'])) {

            $html_assets = new $type();

            app()->bind('ecosystem', function ($app) use ($html_assets) {
                return new Ecosystem($html_assets, new HtmlBuilder);
            });
        }

        return $next($request);
    }
}
