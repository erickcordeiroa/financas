<tr>
    <td><a href="#" class="text-bold">{{ $invoice->description }}</a></td>
    <td>
        <?php
        $now = new DateTime();
        $due = new DateTime($invoice->due_at);
        $expire = $now->diff($due);
        $s = ($expire->days == 1 ? "" : "s");

        if (!$expire->days && $expire->invert):?>

        <span class="date text-warning">Hoje</span>
        <?php elseif (!$expire->invert): ?>
        <span class="{{$expire->days <= 1 ? 'text-primary' : "text-dark"}}">Em {{ $expire->days <= 1 ? '1 dia' : "{$expire->days} dias" }}</span>
        <?php else: ?>
        <span class="date text-danger">Há
            {{ $expire->days <= 1 ? '1 dia' : "{$expire->days} dias" }}</span>
        <?php endif; ?>
    </td>
    <td><span class="badge {{ $invoice->status == 'paid' ? 'badge-success' : 'badge-danger' }} badge-success">{{ $invoice->status == 'paid' ? 'Recebido' : 'Em Aberto' }}</span></td>
    <td>
        <div class="sparkbar" data-color="#00a65a" data-height="20">
            R$&nbsp;{{ number_format($invoice->value, 2, ',', '.') }}</div>
    </td>
</tr>
