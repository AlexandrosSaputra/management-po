<x-layout>
    {{-- @foreach ($dapur as $item)
        <p>{{$item->id}}</p>
    @endforeach --}}
    <livewire:search-dropdown :items="$dapur" name="dapur_id" label="Dapur" id="dapur_id"/>
</x-layout>
