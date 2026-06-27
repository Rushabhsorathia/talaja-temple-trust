<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::active()->orderByDesc('id')->get()->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->localized('name'),
            'price' => $p->price,
            'image_path' => $p->image_path,
            'category' => $p->category,
            'stock' => $p->stock,
        ]);

        return Inertia::render('Shop/Index', ['products' => $products]);
    }

    public function cart(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $items = collect($cart)->map(function ($qty, $id) {
            $p = Product::find($id);

            return $p ? ['id' => $p->id, 'name' => $p->localized('name'), 'price' => (float) $p->price, 'qty' => $qty, 'subtotal' => (float) $p->price * $qty] : null;
        })->filter()->values();

        $total = $items->sum('subtotal');

        return Inertia::render('Shop/Cart', ['items' => $items, 'total' => $total]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        $id = $validated['product_id'];
        $cart[$id] = ($cart[$id] ?? 0) + ($validated['qty'] ?? 1);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Added to cart.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate(['product_id' => ['required', 'exists:products,id']]);
        $cart = $request->session()->get('cart', []);
        unset($cart[$validated['product_id']]);
        $request->session()->put('cart', $cart);

        return back();
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_email' => ['nullable', 'email'],
            'customer_mobile' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:1000'],
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.cart')->withErrors('Cart is empty.');
        }

        $items = collect($cart)->map(function ($qty, $id) {
            $p = Product::findOrFail($id);

            return ['product' => $p, 'name' => $p->localized('name'), 'price' => (float) $p->price, 'qty' => $qty];
        });

        $subtotal = $items->sum(fn ($i) => $i['price'] * $i['qty']);
        $shipping = $subtotal > 1000 ? 0 : 60;
        $tax = round($subtotal * 0.05, 2);
        $total = $subtotal + $shipping + $tax;

        $order = Order::create([
            'order_no' => 'ORD-'.strtoupper(uniqid()),
            'user_id' => $request->user()?->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_mobile' => $validated['customer_mobile'],
            'shipping_address' => $validated['shipping_address'],
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => 'pending',
            'fulfilment_status' => 'new',
        ]);

        foreach ($items as $i) {
            $order->items()->create([
                'product_id' => $i['product']->id,
                'name' => $i['name'],
                'price' => $i['price'],
                'quantity' => $i['qty'],
                'total' => $i['price'] * $i['qty'],
            ]);
            $i['product']->decrement('stock', $i['qty']);
        }

        // TODO: Razorpay checkout (M5-T1). For now mark paid in dev.
        $order->update(['payment_status' => 'paid']);
        $request->session()->forget('cart');

        return redirect()->route('shop.orders')->with('success', 'Order placed! '.$order->order_no);
    }

    public function orders(Request $request)
    {
        $query = Order::with('items')->orderByDesc('id');
        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        }

        $orders = $query->get()->map(fn ($o) => [
            'order_no' => $o->order_no,
            'total' => $o->total,
            'payment_status' => $o->payment_status,
            'fulfilment_status' => $o->fulfilment_status,
            'items' => $o->items->map(fn ($i) => ['name' => $i->name, 'qty' => $i->quantity, 'price' => $i->price]),
            'created_at' => $o->created_at->format('d-m-Y'),
        ]);

        return Inertia::render('Shop/Orders', ['orders' => $orders]);
    }
}
