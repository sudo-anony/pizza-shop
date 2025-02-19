<?php
$badgeTypes=['badge-primary','badge-primary','badge-warning','badge-info','badge-default','badge-warning','badge-success','badge-success','badge-danger','badge-danger','badge-success','badge-success','badge-danger','badge-success','badge-success','badge-danger'];
$classMap = [
    '1'  => 'badge-primary',
    '2'  => 'badge-primary',
    '3'  => 'badge-warning',
    '4'  => 'badge-info',
    '5'  => 'badge-default',
    '6'  => 'badge-warning',
    '7'  => 'badge-success',
    '8'  => 'badge-success',
    '9'  => 'badge-danger',
    '10' => 'badge-danger',
    '11' => 'badge-success',
    '12' => 'badge-success',
    '13' => 'badge-danger',
    '14' => 'badge-success',
    '15' => 'badge-success',
    '16' => 'badge-danger',
    '17' => 'badge-danger',
    '22' => 'badge-success',
];

?>

@if($order->status->count()>0)
<?php
    $final_statuses = [];
    $statusId = $order->status->pluck('id')->last();
    $badgeClass = isset($badgeTypes[$statusId]) ? $badgeTypes[$statusId] : 'badge-danger';
    if($statusId == 22){
        $badgeClass = 'badge-success';
        $statuses = $order->status->pluck('id')->take(-2);
        $final_statuses = [];
        foreach($statuses as $status){
            $final_statuses[] = [
                'id' => $status,
                'alias' => $order->status->where('id', $status)->first()->alias,
                'class' => $classMap[$status] ?? 'badge-success'
            ];
        }
    }
    ?>
    @if($final_statuses)
        @foreach($final_statuses as $status)
            <span class="badge {{ $status['class'] }} badge-pill">
                {{ __($status['alias']) }}
            </span>
        @endforeach
    @else
        <span class="badge {{ $badgeClass }} badge-pill">
            {{ __($order->status->pluck('alias')->last()) }}
        </span>
    @endif
@endif  