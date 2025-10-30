<?php

use App\Models\Carton;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Section;
use App\Models\Unit;
use App\Models\UserActivity;
use App\Models\Warehouse;
use App\Models\Workspace;
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

function workspaces()
{
    $records = Workspace::get([
        'id',
        'name',
    ]);
    return $records;
}
