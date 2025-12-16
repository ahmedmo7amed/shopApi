<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:8px; }
    </style>
</head>
<body>

<h2>Invoices Report</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->customer_name }}</td>
                <td>{{ $invoice->status }}</td>
                <td>{{ $invoice->total }}</td>
                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
