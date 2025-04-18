@php
    $record = $getRecord();
    $posterId = $record?->poster_file_id ?? null;
    $media = $posterId ? \App\Models\MediaFile::query()->find($posterId) : null;
    $media2 = \App\Models\MediaFile::query()->find($posterId);
@endphp

@if ($media)
    <div class="mb-4">
        <span>{{$record}}</span>
        <img src="{{ $media->url }}" alt="Vista previa del cartel" class="rounded-lg shadow w-full max-w-xs">
    </div>
@else
    <span>{{$record}}</span>
    <span>asdf{{$media2}}</span>
    <img src="/media/{{$record->poster_file_id}}" alt="adsfadf">
    <p class="text-sm text-gray-500 italic">No se ha subido ningún cartel.</p>
@endif
