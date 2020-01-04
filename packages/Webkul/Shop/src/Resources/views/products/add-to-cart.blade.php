{!! view_render_event('bagisto.shop.products.add_to_cart.before', ['product' => $product]) !!}

<button type="submit" class="btn btn-lg btn-primary addtocart" {{ $product->type != 'configurable' && ! $product->haveSufficientQuantity(1) ? 'disabled' : '' }}>
    {!!  __('<i class="fas fa-shopping-basket"></i>&nbsp; Přidat do košíku') !!}
</button>

{!! view_render_event('bagisto.shop.products.add_to_cart.after', ['product' => $product]) !!}