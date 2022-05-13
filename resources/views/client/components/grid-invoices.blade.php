<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Vencimento</th>
                    <th>Categoria</th>
                    <th>Parcela</th>
                    <th>Status</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @if (!$invoice->isEmpty())
                    @foreach ($invoice as $item)
                        <tr>
                            <td><a href="{{ route('app.invoice', ['id' => $item->id]) }}"
                                    class="text-bold">{{ $item->description }}</a></td>
                            <td> Dia {{ (new DateTime($item->due_at))->format('d/m') }}</td>
                            <td>{{ $item->categories->name }}</td>
                            @if ($item->repeat_when == 'enrollment')
                                <td>{{ $item->enrollments_of }} de {{ $item->enrollments }}</td>
                            @elseif($item->repeat_when == 'fixed')
                                <td><i class="fas fa-exchange-alt"></i> Fixa</td>
                            @else
                                <td>Única</td>
                            @endif
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
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">
                            <p class="alert alert-info"><i class="fas fa-exclamation-triangle"></i> No
                                momento, não existem contas registradas. Comece lançando agora mesmo! </p>
                        </td>
                    </tr>
                @endif
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
