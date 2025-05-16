@extends('base.base')

@section('content')
    <h1>Edit Product</h1>
    <form action="{{ route('edit_product', $product->id) }}" class="row g-3" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="col-md-6">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ $product->name }}">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-md-6">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror"
                   id="price" name="price" value="{{ $product->price }}">
            @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-12">
            <label for="details" class="form-label">Product Details</label>
            <input type="text" class="form-control @error('details') is-invalid @enderror"
                   id="details" name="details" placeholder="Input product details here..."
                   value="{{ $product->details }}">
            @error('details')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-md-6">
            <label for="product_category" class="form-label">Product Category</label>
            <select id="product_category" class="form-select @error('product_category') is-invalid @enderror"
                    name="product_category">
                <option value="" disabled>Select a Product Category</option>
                @foreach ($product_categories as $pc)
                    <option value="{{ $pc->id }}" {{ $pc->id == $product->category_id ? 'selected' : '' }}>
                        {{ $pc->name }}
                    </option>
                @endforeach
            </select>
            @error('product_category')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-md-6">
            <label for="stock" class="form-label">Initial Stock</label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                   id="stock" name="stock" value="{{ $product->stock }}">
            @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Edit Product</button>
            <a href="{{ route('store') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
    
@endsection