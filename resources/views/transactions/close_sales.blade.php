@extends('layouts.app')
@section('content')

<h1>Close Sales Summary</h1>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity Sold</th>
            <th>Remaining Stock</th>
            <th>Profit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($summary as $item)
        <tr>
            <td>{{ $item['product']->name }}</td>
            <td>{{ $item['total_sold'] }}</td>
            <td>{{ $item['remaining_stock'] }}</td>
            <td>{{ number_format($item['profit'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
