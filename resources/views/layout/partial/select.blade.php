<option value="">{{ $title }}</option>
@foreach ($records as $item)
    <option value="{{ $item->id }}">{{ $item->name??$item->reference }}</option>
@endforeach
