<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Valor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice as $item)
                    <tr>
                        <td>{{ (new DateTime($item->due_at))->format('d/m/Y') }}</td>
                        <td><a href="#" class="text-bold">{{ $item->description }}</a></td>
                        <td>{{ $item->categories->name }}</td>
                        <td>
                            <span
                                class="badge {{ $item->status == 'paid' ? 'badge-success' : 'badge-danger' }} badge-success">
                                @if ($item->type == 'income')
                                    {{ $item->status == 'paid' ? 'Recebido' : 'Em Aberto' }}
                                @else
                                    {{ $item->status == 'paid' ? 'Pago' : 'Em Aberto' }}
                                @endif
                            </span>
                        </td>
                        <td>{{ number_format($item->value, 2, ',', '.') }}</td>
                        <td>
                            {!! $item->status == 'paid' ? '<i class="text-success far fa-thumbs-up"></i>' : '<i class="text-danger far fa-thumbs-down"></i>' !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        @if (isset($filters))
            {{ $invoice->appends($filters)->links() }}
        @else
            {{ $invoice->links() }}
        @endif

    </div>
</div>
