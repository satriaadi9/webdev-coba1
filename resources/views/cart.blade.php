@extends('base.base')

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg z-3" role="alert" style="min-width: 300px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('content')
<h4>My Cart</h4>
<table class="table">
    <thead>
        <tr>
            <th>Name</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach ($cart as $id => $item)
            @php $total += $item['price'] * $item['quantity']; @endphp
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>Rp{{ number_format($item['price'],0,',','.') }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>Rp{{ number_format($item['price'] * $item['quantity'],0,',','.') }}</td>
                <td>
                    <form action="{{ route('remove_from_cart', $id) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h5>Total: Rp{{ number_format($total,0,',','.') }}</h5>

<form action="{{ route('checkout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Checkout</button>
</form>

@endsection