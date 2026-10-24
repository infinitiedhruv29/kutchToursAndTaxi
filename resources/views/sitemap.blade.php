{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticRoutes as $route)
    <url>
        <loc>{{ $route['url'] }}</loc>
        <changefreq>{{ $route['changefreq'] }}</changefreq>
        <priority>{{ $route['priority'] }}</priority>
    </url>
@endforeach
@foreach($packages as $pkg)
    <url>
        <loc>{{ route('package.show', $pkg->slug) }}</loc>
        <lastmod>{{ $pkg->updated_at?->tz('UTC')->toAtomString() ?? now()->tz('UTC')->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
</urlset>
