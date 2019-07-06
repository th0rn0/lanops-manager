<nav class="navbar navbar-default navbar-shop">
	<div class="container-fluid">
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				@foreach ($allCategories as $c)
					@if (isset($category) && $c->id == $category->id)
						<li class="active"><a href="/shop/{{ $c->slug }}">{{ $c->name }}</a></li>
					@else
						<li><a href="/shop/{{ $c->slug }}">{{ $c->name }}</a></li>
					@endif
				@endforeach
			</ul>
			</ul>
			<ul class="nav navbar-nav navbar-right">
			  <li><a href="/shop/basket">Basket</a></li>
			  <li><a href="/payment/checkout">Checkout</a></li>
			  <li><a href="/shop/orders">Orders</a></li>
			</ul>
		</div>
	</div>
</nav>