<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Closed Summary</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; color: #333; margin: 0; padding: 0; }
        .container { width: 95%; max-width: 700px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background-color: #4CAF50; color: #fff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 20px; }
        .content h2 { color: #4CAF50; }
        .summary { margin-top: 20px; }
        .summary p { margin: 5px 0; font-size: 16px; }
        .table-wrapper { overflow-x: auto; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; min-width: 600px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; font-size: 14px; }
        th { background-color: #f4f4f4; color: #333; }
        .footer { text-align: center; padding: 15px; background-color: #f4f4f4; font-size: 14px; color: #666; }
        @media screen and (max-width: 600px) {
            .content h2 { font-size: 18px; }
            th, td { font-size: 12px; padding: 6px; }
            .summary p { font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Daily Sales Closed Summary</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="content">
            <h2>Hello Owner,</h2>
            <p>Today's sales have been successfully closed. Here is a quick summary of the performance:</p>

            <div class="summary">
                <p><strong>Total Sales:</strong> {{ number_format($totalSales, 2) }} Tsh</p>
                <p><strong>Total Discount Given:</strong> {{ number_format($totalDiscount, 2) }} Tsh</p>
                <p><strong>Total Profit:</strong> {{ number_format($totalProfit, 2) }} Tsh</p>
            </div>

            <h2>Closed Transactions</h2>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Profit</th>
                            <th>Closed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                        <tr>
                            <td>{{ $t->product->name ?? 'N/A' }}</td>
                            <td>{{ $t->quantity }}</td>
                            <td>{{ number_format($t->unit_cost, 2) }}</td>
                            <td>{{ number_format($t->unit_price, 2) }}</td>
                            <td>{{ number_format($t->discount, 2) }}</td>
                            <td>{{ number_format(($t->unit_price - $t->unit_cost) * $t->quantity - $t->discount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->closed_at)->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p style="margin-top:20px;">Keep up the great work! This summary helps you track sales and make informed decisions for your business.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>
</body>
</html>
