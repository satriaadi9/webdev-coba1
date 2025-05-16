<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use GuzzleHttp\Handler\Proxy;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    //
    public function show(){
        return view('store',[
            'products'=>Product::with(['product_category'])->get()
        ]);
    }

    public function product_insert_form(){
       return view('product.insert-form',[
            'product_categories'=>ProductCategory::get()
       ]);
    }

    public function product_edit_form(Product $product){
        return view('product.edit-form',[
            'product'=>$product,
            'product_categories'=>ProductCategory::get()
        ]);
    }

    public function insert_product(Request $request){
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category' => 'required',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'product_category.required' => 'Kategori produk harus dipilih.',
        ]);

        $product = new Product;

        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->product_category;

        $product->save();

        return redirect(route('store'))
        ->with('success','Produk berhasil ditambahkan!');
    }

    public function edit_product(Request $request, Product $product){
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'product_category' => 'required',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'product_category.required' => 'Kategori produk harus dipilih.',
        ]);

        $product->name = $request->name;
        $product->details = $request->details;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->product_category;

        $product->save();

        return redirect(route('store'))
        ->with('success','Produk berhasil diedit!');
    }

    public function delete_product(Product $product){
        $product->delete();

        return redirect(route('store'))->with('success', 'Produk berhasil dihapus!');
    }

    public function addToCart(Request $request, Product $product){
        $cart = session()->get('cart',[]);

        if(isset($cart[$product->id])){
            $cart[$product->id]['quantity']++;
        }
        else{
            $cart[$product->id] = [
                'name'=>$product->name,
                'price'=>$product->price,
                'quantity'=>1
            ];
        }

        session(['cart'=>$cart]);
        return redirect()->back()->with('success','Product added to cart!');
    }

    public function cart(){
        $cart = session()->get('cart', []);
        return view('cart',compact('cart'));
    }

    public function removeFromCart($id){
        $cart = session()->get('cart',[]);
        if(isset($cart[$id])){
            unset($cart[$id]);
            session(['cart'=>$cart]);
        }

        return redirect()->back()->with('success','Product removed from cart!');
    }

    public function checkout(Request $request){
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();
        try {
            $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'user_id' => Auth::id(),
                'customer_name' => Auth::user()->name,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_url' => null,
            ]);

            foreach ($cart as $product_id => $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }


            // Midtrans Config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Create Midtrans Transaction
            $params = [
                'transaction_details' => [
                    'order_id' => $order->invoice_number,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
				'callbacks' => [
                    'finish' => route('store'),
                ]
            ];

            $snapUrl = Snap::createTransaction($params)->redirect_url;
            // Save Payment URL
            $order->payment_url = $snapUrl;
            $order->save();
            
            DB::commit();

            session()->forget('cart'); // Clear cart
            return redirect($snapUrl);

            //return redirect()->route('store')->with('success', 'Checkout berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
