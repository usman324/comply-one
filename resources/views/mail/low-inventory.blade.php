<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Low Inventory Email</title>
    <style>
        * {
            font-family: sans-serif;
            /* Change your font family */
        }

        .content-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            min-width: 400px;
            border-radius: 5px 5px 0 0;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .content-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .content-table th,
        .content-table td {
            padding: 12px 15px;
        }

        .content-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .content-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .content-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .content-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }
    </style>
</head>

<body>

    <table class="content-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Part Number</th>
                <th>Category</th>
                <th>Purchase Orders</th>
                <th>Work Orders</th>
                <th>Current Stock</th>
                <th>Alert Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->part_number }}</td>
                    <td>{{ $item?->partCategory?->title }}</td>
                    <td>{{ $item->inventoryInQty() }}</td>
                    <td>{{ $item->inventoryOutQty() }}</td>
                    <td>{{ $item->totalRemainingQty() }}</td>
                    <td>{{ $item->alert_qty }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
