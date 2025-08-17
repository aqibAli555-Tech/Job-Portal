<h2 class="title"> {{ t('live_commission_table') }}</h2>
<div class="table-responsive">
    <table class="table v-middle mb-0 table-striped table-sm">
        <thead>
        <tr>
            <th>{{ t('min_amount') }}($)</th>
            <th>{{ t('max_amount') }}($)</th>
            <th>{{ t('commission') }}(%)</th>
        </tr>
        </thead>
        <tbody>
            @if ($data['commission_slots']->count() > 0)
            @foreach($data['commission_slots'] as $commissionSlot)
            <tr class="{{ isset($data['slot']) && $data['slot']->id == $commissionSlot->id ? 'bg-warning text-dark' : '' }}">
                <td >${{ number_format($commissionSlot->min_amount) }}</td>
                <td >${{ number_format($commissionSlot->max_amount) }}{{ $commissionSlot->max_amount == 250000 ? '+' : '' }}</td>
                <td >{{ $commissionSlot->commission }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">
                    <p>No Record found</p>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>