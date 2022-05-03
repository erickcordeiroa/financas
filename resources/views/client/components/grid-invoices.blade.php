<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Valor</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice as $item)
                    <tr>
                        <td>{{ (new DateTime($item->due_at))->format('d/m/Y') }}</td>
                        <td>{{ $item->description }}</td>
                        <td>
                            <span
                                class="badge {{ $item->status == 'paid' ? 'badge-success' : 'badge-danger' }} badge-success">
                                {{ $item->status == 'paid' ? 'Recebido' : 'Em Aberto' }}</span>
                        </td>
                        <td>{{ number_format($item->value, 2, ',', '.') }}</td>
                        <td>
                            {!!($item->status == 'paid')? '<i class="text-success far fa-thumbs-up"></i>' : '<i class="text-danger far fa-thumbs-down"></i>'!!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
