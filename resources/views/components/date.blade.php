<span x-data='{ date: @json((int)$date->valueOf()) }'
    x-text="new Date(date).toLocaleString('nl-NL', {month: '2-digit', day: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric'})">
    <noscript>{{ $date }}</noscript>
</span>
