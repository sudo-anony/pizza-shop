<?php
$badgeTypes=['badge-primary','badge-primary','badge-warning','badge-info','badge-default','badge-warning','badge-success','badge-success','badge-danger','badge-danger','badge-success','badge-success','badge-danger','badge-success','badge-success','badge-danger'];
?>

@if($order->status->count()>0)
<?php
    $statusId = $order->status->pluck('id')->last();
    $badgeClass = isset($badgeTypes[$statusId]) ? $badgeTypes[$statusId] : 'badge-danger';
    ?>
    <span class="badge {{ $badgeClass }} badge-pill">
        {{ __($order->status->pluck('alias')->last()) }}
    </span>
@endif  