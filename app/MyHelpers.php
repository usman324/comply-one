<?php

use App\Models\Carton;
use App\Models\Customer;
use App\Models\GeneralSetting;
use App\Models\Section;
use App\Models\Unit;
use App\Models\UserActivity;
use App\Models\Warehouse;
use App\Models\Workspace;
use Carbon\Carbon;
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

function formatBytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }

    return round($bytes, $precision) . ' ' . $units[$i];
}

function getFileIcon($extension)
{
    $icons = [
        'pdf' => 'ri-file-pdf-line',
        'doc' => 'ri-file-word-line',
        'docx' => 'ri-file-word-line',
        'xls' => 'ri-file-excel-line',
        'xlsx' => 'ri-file-excel-line',
        'ppt' => 'ri-file-ppt-line',
        'pptx' => 'ri-file-ppt-line',
        'jpg' => 'ri-image-line',
        'jpeg' => 'ri-image-line',
        'png' => 'ri-image-line',
        'gif' => 'ri-image-line',
        'webp' => 'ri-image-line',
        'svg' => 'ri-image-line',
        'mp4' => 'ri-video-line',
        'avi' => 'ri-video-line',
        'mov' => 'ri-video-line',
        'wmv' => 'ri-video-line',
        'mp3' => 'ri-music-line',
        'wav' => 'ri-music-line',
        'ogg' => 'ri-music-line',
        'zip' => 'ri-file-zip-line',
        'rar' => 'ri-file-zip-line',
        '7z' => 'ri-file-zip-line',
        'txt' => 'ri-file-text-line',
        'html' => 'ri-code-line',
        'css' => 'ri-code-line',
        'js' => 'ri-code-line',
        'php' => 'ri-code-line',
        'py' => 'ri-code-line',
        'java' => 'ri-code-line',
    ];

    return $icons[strtolower($extension)] ?? 'ri-file-text-line';
}
function getFileTypeFromMime($mimeType)
{
    if (str_contains($mimeType, 'image')) {
        return 'images';
    }

    if (str_contains($mimeType, 'video')) {
        return 'video';
    }

    if (str_contains($mimeType, 'audio')) {
        return 'music';
    }

    return 'documents';
}

function getMimeTypeIcon($mimeType)
{
    if (str_contains($mimeType, 'image')) {
        return 'text-info';
    }

    if (str_contains($mimeType, 'video')) {
        return 'text-danger';
    }

    if (str_contains($mimeType, 'audio')) {
        return 'text-success';
    }

    if (str_contains($mimeType, 'pdf')) {
        return 'text-danger';
    }

    return 'text-primary';
}

function getFileExtension($filename)
{
    return pathinfo($filename, PATHINFO_EXTENSION);
}
function generateUniqueFilename($originalName)
{
    $extension = getFileExtension($originalName);
    $filename = pathinfo($originalName, PATHINFO_FILENAME);
    $uniqueId = uniqid();
    $timestamp = time();

    return "{$filename}_{$uniqueId}_{$timestamp}.{$extension}";
}

/**
 * Sanitize filename
 *
 * @param string $filename
 * @return string
 */
function sanitizeFilename($filename)
{
    // Remove any path information
    $filename = basename($filename);

    // Remove special characters except dots, underscores, and hyphens
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

    // Remove multiple underscores
    $filename = preg_replace('/_+/', '_', $filename);

    // Trim underscores from start and end
    $filename = trim($filename, '_');

    return $filename;
}

/**
 * Check if file is an image
 *
 * @param string $mimeType
 * @return bool
 */
function isImageFile($mimeType)
{
    return str_starts_with($mimeType, 'image/');
}

/**
 * Check if file is a video
 *
 * @param string $mimeType
 * @return bool
 */
function isVideoFile($mimeType)
{
    return str_starts_with($mimeType, 'video/');
}

/**
 * Check if file is audio
 *
 * @param string $mimeType
 * @return bool
 */
function isAudioFile($mimeType)
{
    return str_starts_with($mimeType, 'audio/');
}

/**
 * Get file size upload limit in bytes
 *
 * @return int
 */
function getFileSizeLimit()
{
    return 100 * 1024 * 1024; // 100 MB
}

/**
 * Check if file size is within allowed limit
 *
 * @param int $fileSize
 * @return bool
 */
function isFileSizeAllowed($fileSize)
{
    return $fileSize <= getFileSizeLimit();
}

/**
 * Get list of allowed mime types
 *
 * @return array
 */
function getAllowedMimeTypes()
{
    return [
        // Documents
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',

        // Images
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',

        // Videos
        'video/mp4',
        'video/mpeg',
        'video/quicktime',
        'video/x-msvideo',

        // Audio
        'audio/mpeg',
        'audio/wav',
        'audio/ogg',

        // Archives
        'application/zip',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
    ];
}

/**
 * Check if mime type is allowed
 *
 * @param string $mimeType
 * @return bool
 */
function isMimeTypeAllowed($mimeType)
{
    return in_array($mimeType, getAllowedMimeTypes());
}
