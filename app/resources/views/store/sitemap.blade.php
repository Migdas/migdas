{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc>{{ route('store.index') }}</loc></url>
    <url><loc>{{ route('store.terms') }}</loc></url>
    <url><loc>{{ route('store.privacy') }}</loc></url>
@foreach($products as $product)
    <url>
        <loc>{{ route('store.product', $product) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
    </url>
@endforeach
</urlset>
