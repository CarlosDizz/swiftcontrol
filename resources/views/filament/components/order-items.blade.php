<table class="table-auto w-full text-sm">
    <thead>
    <tr>
        <th class="text-left">Tipo de entrada</th>
        <th class="text-left">Cantidad</th>
        <th class="text-left">Precio unitario</th>
        <th class="text-left">Precio total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->priceRange->type ?? '—' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->unit_price, 2) }} €</td>
            <td>{{ number_format($item->unit_price, 2) * $item->quantity }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>
