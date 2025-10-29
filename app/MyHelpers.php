<?php

use App\Models\Brand;
use App\Models\Carton;
use App\Models\Category;
use App\Models\Color;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\InventoryRoom;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Rack;
use App\Models\Section;
use App\Models\Size;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\UserActivity;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

function toString($value)
{
    return '"' . (string)($value) . '"';
}
// function generateUUID($length)
// {
//     $random = '';
//     for ($i = 0; $i < $length; $i++) {
//         $random .= rand(0, 1) ? rand(0, 5) : chr(rand(ord('A'), ord('Z')));
//     }
//     return $random;
// }
function generateUUID($length)
{
    // Generate random 13-digit number
    $barcode = str_pad(mt_rand(1, 99999), $length, '0', STR_PAD_LEFT);

    return $barcode;
}
function deleteImage($path, $file)
{
    if ($file && file_exists(storage_path('app/public/' . $path))) {
        unlink(storage_path('app/public/' . $path));
        // echo "File deleted successfully.";
    }
}
function dateFormat($date)
{
    if (isset($date)) {
        // return $date;
        return date('d/m/Y', strtotime($date));
    }
}
function getUser()
{
    if (auth()->check()) {
        $user = auth()->user();
        return $user;
    }
}
function assignUnits()
{
    if (!getUser()->hasRole('admin')) {
        $units = getUser()->units?->pluck('unit_id');
        return Unit::whereIn('id', $units)->get();
    }
    return Unit::get();
}
function timeFormat($time)
{
    if (isset($time)) {
        return date('h:i A', strtotime($time));
    }
}
function dateDefualtFormat($time)
{
    if (isset($time)) {
        return date('Y-m-d', strtotime($time));
    }
}
function hourFormat($time)
{
    if (isset($time)) {
        return date('H', strtotime($time));
    }
}
function dayformat($time)
{
    if (isset($time)) {
        return date('l', strtotime($time));
    }
}
function minFormat($time)
{
    if (isset($time)) {
        return date('i', strtotime($time));
    }
}


function myStrPad($str, $length = 2)
{
    return str_pad($str, $length, "0", STR_PAD_LEFT);
}
function gs()
{
    $gs = GeneralSetting::first();
    return $gs;
}
function sections()
{
    $gs = Section::all();
    return $gs;
}
function formatLocation($carton)
{
    $parts = [];

    // if ($carton->current_warehouse_id) {
    //     $parts[] = "WH: {$carton->currentWarehouse->name}";
    // }
    // if ($carton->current_unit_id) {
    //     $parts[] = "Unit: {$carton->currentUnit->name}";
    // }
    if ($carton->current_inventory_room_id) {
        $parts[] = "{$carton->currentRoom->name}";
    }
    if ($carton->current_rack_id) {
        $parts[] = "{$carton->currentRack->name}";
    }
    if ($carton->position) {
        $parts[] = "{$carton->position}";
    }

    return implode(' - ', $parts);
}
function prepareCartonLabelData($carton)
{
    return [
        'carton_id' => $carton->carton_id,
        'location' => formatLocation($carton),
        'products' => $carton->cartonProducts->map(function ($cp) {
            return [
                'product_name' => $cp->product->getTitle() ?? 'N/A',
                'color' => $cp->color?->name ?? 'N/A',
                'size' => $cp->size?->name ?? 'N/A',
                'quantity' => $cp->quantity_inside,
            ];
        }),
        'total_pieces' => $carton->cartonProducts->sum('quantity_inside'),
        'received_date' => $carton->received_date,
        'position' => $carton->position,
        'status' => $carton->status,
        'notes' => $carton->notes,
    ];
}

function generateCartonId()
{
    $lastCarton = Carton::orderBy('id', 'desc')->first();
    $nextNumber = $lastCarton ? (int)substr($lastCarton->carton_id, 2) + 1 : 1;
    return 'C-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
}
function customers()
{
    $gs = Customer::all();
    return $gs;
}
function dateTimeFormat($time)
{
    if (isset($time)) {
        return date('Y-m-d h:i A', strtotime($time));
    }
}
function baseConversion($address)
{
    $path = $address;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    return $logo_base64;
}
function numFormat($number, $decimals = 2)
{
    return floatval(round($number, $decimals));
}

function numberFormat($number, $decimals = 2)
{
    return floatval(round($number, $decimals));
    //    return number_format($number, $decimals);
}
function timeDuration($start, $end)
{
    $start = Carbon::parse($start);
    $end = Carbon::parse($end);
    // Calculate total task duration in minutes
    $totalDuration = $start->diffInMinutes($end);

    $hours = intdiv($totalDuration, 60);
    $minutes = $totalDuration % 60;

    return $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
}
function saveImage($path, $image)
{
    $name = rand(10, 100) . time() . '.' . $image->getClientOriginalExtension();
    $image->storeAs($path, $name);
    $image_name = $name;
    return $image_name;
}
function myImgFit($path, $real_img, $h = null, $w = null, $name = null)
{
    $avatar = rand(10, 100) . time() . '.' . $real_img->getClientOriginalExtension();
    $img = Image::make($real_img->getRealPath());

    $img->fit($h, $w, function ($constraint) {
        $constraint->upsize();
    });
    $imageData = $img->encode();
    Storage::put($path . $avatar, $imageData);
    return $avatar;
}
function totalDuration($start, $end)
{
    $start = Carbon::parse($start);
    $end = Carbon::parse($end);
    // Calculate total task duration in minutes
    $totalDuration = $start->diffInMinutes($end);


    return $totalDuration;
}

function saveActivity($data, $user_id = null)
{
    $data['user_id'] = $user_id ?: auth()->id();
    UserActivity::create($data);
}

function makeDebitSalesArray($array)
{
    return array_map(function ($key, $value) {
        $remarks = $value['notes'];
        return [
            'id' => $value['id'],
            'link' => url('sales/' . $value['id']),
            'notes' => $remarks,
            'debit' => $value['total_amount'],
            'credit' => 0.00,
            'type' =>  $value['reference'],
            'date' => $value['date'],
        ];
    }, array_keys($array), $array);
}
function makeCreditPurchaseArray($array)
{
    return array_map(function ($key, $value) {
        $remarks = $value['notes'];
        $v_type = 'PI';
        return [
            'id' => $value['id'],
            'link' => url('purchases/' . $value['id']),
            'notes' => $remarks,
            'debit' => 0.00,
            'credit' => $value['total_amount'],
            'type' =>  $value['reference'],
            'date' => $value['date'],
        ];
    }, array_keys($array), $array);
}
function makeCreditReceiptArray($array)
{
    return array_map(function ($key, $value) {

        $type = $value['type'];
        $remarks = $value['notes'];
        $credit = $value['amount'];

        return [
            'id' => $value['id'],
            'link' => url('payment-' . $type . 's/' . $value['id']),
            'notes' => $remarks,
            'debit' => 0.00,
            'credit' => $credit,
            'type' =>  $value['reference'],
            'date' => $value['date'],
        ];
    }, array_keys($array), $array);
}


function makeDebitReceiptArray($array)
{
    return array_map(function ($key, $value) {

        $remarks = $value['description'];
        $debit = $value['amount'];
        $v_type = 'SPI';
        return [
            'id' => $value['id'],
            'link' => url('payments/' . $value['id']),
            'notes' => $remarks,
            'debit' => $debit,
            'credit' => 0.00,
            'type' => $value['reference'],
            'date' => $value['date'],
        ];
    }, array_keys($array), $array);
}
function checkStock($type, $product_id, $warehouse_id, $unit_id)
{

    // 1️⃣ Purchases
    $purchasesAgg = DB::table('purchase_products as pp')
        ->join('purchases as p', 'p.id', '=', 'pp.purchase_id')
        ->select(
            'pp.product_id',
            DB::raw('SUM(pp.qty) as purchased_qty'),
            DB::raw('SUM(pp.piece_qty) as purchased_pieces')
        )
        ->whereNull('pp.deleted_at')
        ->where('p.warehouse_id', $warehouse_id)
        ->where('pp.product_id', $product_id)
        ->groupBy('pp.product_id');

    // 2️⃣ Transfer IN
    $transferInAgg = DB::table('transfer_products as tp')
        ->join('transfers as t', 't.id', '=', 'tp.transfer_id')
        ->select(
            'tp.product_id',
            DB::raw('SUM(tp.qty) as transfer_in_qty'),
            DB::raw('SUM(tp.piece_qty) as transfer_in_pieces')
        )
        ->whereNull('tp.deleted_at')
        ->when($unit_id, function ($q) use ($unit_id) {
            $q->where('t.to_unit_id', $unit_id);
        })
        ->where('tp.product_id', $product_id)
        ->groupBy('tp.product_id');

    // 3️⃣ Transfer OUT
    $transferOutAgg = DB::table('transfer_products as tp')
        ->join('transfers as t', 't.id', '=', 'tp.transfer_id')
        ->select(
            'tp.product_id',
            DB::raw('SUM(tp.qty) as transfer_out_qty'),
            DB::raw('SUM(tp.piece_qty) as transfer_out_pieces')
        )
        ->whereNull('tp.deleted_at')
        ->when($unit_id, function ($q) use ($unit_id) {
            $q->where('t.from_unit_id', $unit_id);
        })
        ->where('tp.product_id', $product_id)
        ->groupBy('tp.product_id');

    // 4️⃣ Production usage
    $usedAgg = DB::table('production_uses as pu')
        ->select(
            'pu.product_id',
            DB::raw('SUM(pu.qty_used) as used_qty'),
            DB::raw('SUM(pu.piece_used) as used_pieces')
        )
        ->whereNull('pu.deleted_at')
        ->when($unit_id, function ($q) use ($unit_id) {
            $q->where('pu.unit_id', $unit_id);
        })
        ->where('pu.product_id', $product_id)
        ->groupBy('pu.product_id');

    if ($type === 'variation') {


        $currentStock = ProductVariation::query()
            ->select([
                'product_variations.id as variation_id',
                'products.title as product_name',
                DB::raw('COALESCE(pur.purchased_qty, 0) as purchased_qty'),
                DB::raw('COALESCE(tin.transfer_in_qty, 0) as transfer_in_qty'),
                DB::raw('COALESCE(tout.transfer_out_qty, 0) as transfer_out_qty'),
                DB::raw('COALESCE(u.used_qty, 0) as used_qty'),
                // DB::raw('(COALESCE(pur.purchased_qty,0)  - COALESCE(tout.transfer_out_qty,0) - COALESCE(u.used_qty,0)) as current_stock_qty'),

                DB::raw('COALESCE(pur.purchased_pieces, 0) as purchased_pieces'),
                DB::raw('COALESCE(tin.transfer_in_pieces, 0) as transfer_in_pieces'),
                DB::raw('COALESCE(tout.transfer_out_pieces, 0) as transfer_out_pieces'),
                DB::raw('COALESCE(u.used_pieces, 0) as used_pieces'),
                // DB::raw('(COALESCE(pur.purchased_pieces,0)  - COALESCE(tout.transfer_out_pieces,0) - COALESCE(u.used_pieces,0)) as current_stock_pieces')
            ])
            ->join('products', 'product_variations.product_id', '=', 'products.id')
            ->leftJoinSub($purchasesAgg, 'pur', 'pur.product_id', '=', 'product_variations.id')
            ->leftJoinSub($transferInAgg, 'tin', 'tin.product_id', '=', 'product_variations.id')
            ->leftJoinSub($transferOutAgg, 'tout', 'tout.product_id', '=', 'product_variations.id')
            ->leftJoinSub($usedAgg, 'u', 'u.product_id', '=', 'product_variations.id')
            ->where('product_variations.id', $product_id) // specific variation id
            ->first();
        dd($currentStock);
    } else {
        $currentStock = Product::query()
            ->select([
                'products.id',
                'products.title as product_name',
                DB::raw('COALESCE(pur.purchased_qty, 0) as purchased_qty'),
                DB::raw('COALESCE(tin.transfer_in_qty, 0) as transfer_in_qty'),
                DB::raw('COALESCE(tout.transfer_out_qty, 0) as transfer_out_qty'),
                DB::raw('COALESCE(u.used_qty, 0) as used_qty'),
                DB::raw('(COALESCE(pur.purchased_qty,0) ) - COALESCE(tout.transfer_out_qty,0) - COALESCE(u.used_qty,0)) as current_stock_qty'),
                DB::raw('COALESCE(pur.purchased_pieces, 0) as purchased_pieces'),
                DB::raw('(COALESCE(pur.purchased_pieces,0)  - COALESCE(tout.transfer_out_pieces,0) - COALESCE(u.used_pieces,0)) as current_stock_pieces')
            ])
            ->leftJoinSub($purchasesAgg, 'pur', 'pur.product_id', '=', 'products.id')
            ->leftJoinSub($transferInAgg, 'tin', 'tin.product_id', '=', 'products.id')
            ->leftJoinSub($transferOutAgg, 'tout', 'tout.product_id', '=', 'products.id')
            ->leftJoinSub($usedAgg, 'u', 'u.product_id', '=', 'products.id')
            ->where('products.id', $product_id)
            ->first();
        dd($currentStock);
    }
    return $currentStock;
}
