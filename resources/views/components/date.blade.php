<span x-data='{ date: @json($date) }'
    x-text="new Date(date + ' UTC').toLocaleString('nl-NL')">
    <noscript>{{ $date }}</noscript>
</span>
