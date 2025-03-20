<div class="table-responsive d-none d-md-block">
    <table class="table align-items-center">
        <thead class="thead-light">
            <tr>
                <th scope="col">{{ __('ID') }}</th>
                @hasrole('admin|driver')
                    <th scope="col">{{ __('Restaurant') }}</th>
                @endif
                <th class="table-web" scope="col">{{ __('Created') }}</th>
                <th class="table-web" scope="col">{{ __('Time Slot') }}</th>
                <th class="table-web" scope="col">{{ __('Method') }}</th>
                <th scope="col">{{ __('Last status') }}</th>
                @hasrole('admin|owner')
                    <th class="table-web" scope="col">{{ __('Refund') }}</th>
                @endif
                @hasrole('admin|owner|driver')
                    <th class="table-web" scope="col">{{ __('Client') }}</th>
                @endif
                @if(auth()->user()->hasRole('admin'))
                    <th class="table-web" scope="col">{{ __('Address') }}</th>
                @endif
                @if (!isset($hideAction))
                    @if(auth()->user()->hasRole('owner'))
                        <th class="table-web" scope="col">{{ __('Items') }}</th>
                    @endif
                @endif
                @hasrole('admin|owner')
                    <th class="table-web" scope="col">{{ __('Driver') }}</th>
                @endif
                <th class="table-web" scope="col">{{ __('Price') }}</th>
                <th class="table-web" scope="col">{{ __('Delivery') }}</th>
                @if (!isset($hideAction))
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('owner') || auth()->user()->hasRole('driver'))
                        <th scope="col">{{ __('Actions') }}</th>
                    @endif
                @endif
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
        <tr>
            <td>
                <a class="btn badge badge-success badge-pill" href="{{ route('orders.show',$order->id )}}"> #{{ !empty($order->randomID) ? $order->randomID : $order->id_formated }}</a>
            </td>
            @hasrole('admin|driver')
            <th scope="row">
                <div class="media align-items-center">
                    <a class="avatar-custom mr-3">
                        <img class="rounded" alt="..." src={{ $order->restorant->icon }}>
                    </a>
                    <div class="media-body">
                        <span class="mb-0 text-sm">{{ $order->restorant->name }}</span>
                    </div>
                </div>
            </th>
            @endif

            <td class="table-web">
                {{ $order->created_at->locale(Config::get('app.locale'))->isoFormat('LLLL') }}
            </td>
            <td class="table-web">
                {{ $order->time_formated }}
            </td>
            <td class="table-web">
                <span class="badge badge-primary badge-pill">{{ $order->getExpeditionType() }}</span>
            </td>
            <td>
                @include('orders.partials.laststatus')
            </td>
            @hasrole('admin|owner')
                <td class="table-web">
                    @if ($order->status->contains(22))
                        <span class="badge badge-success badge-pill">{{ __('Refunded') }}</span>
                    @elseif($order->payment_method == 'stripe' && $order->stripe_charge_id != null)
                        <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" style="display:inline;" onsubmit="refundOrder({{ $order->id }}); return false;" id="refund-form-{{ $order->id }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('Refund Order') }}</button>
                        </form>
                    @else
                        <span class="badge badge-danger badge-pill">{{ __('Not Refundable') }}</span>
                    @endif
                </td>
            @endif
            @hasrole('admin|owner|driver')
            <td class="table-web">
                @if ($order->client)
                    {{ $order->client->name }}
                @else
                    {{ $order->getConfig('client_name','') }}
                @endif
            </td>
            @endif
            @if(auth()->user()->hasRole('admin'))
                <td class="table-web">
                    {{ $order->address?$order->address->address:"" }}
                </td>
            @endif

            @if (!isset($hideAction))
                @if(auth()->user()->hasRole('owner'))
                    <td class="table-web">
                        {{ count($order->items) }}
                    </td>
                @endif
            @endif
            @hasrole('admin|owner')
                <td class="table-web">
                    {{ !empty($order->driver->name) ? $order->driver->name : "" }}
                </td>
            @endif
            <td class="table-web">
                @money( $order->order_price_with_discount, config('settings.cashier_currency'),config('settings.do_convertion'))

            </td>
            <td class="table-web">
                @money( $order->delivery_price, config('settings.cashier_currency'),config('settings.do_convertion'))
            </td>
            @if (!isset($hideAction))
                @include('orders.partials.actions.table',['order' => $order ]) 
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="d-block d-md-none">
    @foreach($orders as $order)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">
                <a class="btn badge badge-success badge-pill" href="{{ route('orders.show',$order->id )}}"> #{{ !empty($order->randomID) ? $order->randomID : $order->id_formated }}</a>
            </h5>
            @hasrole('admin|driver')
            <p class="card-text">
                <strong>{{ __('Restaurant') }}:</strong> 
                <div class="media align-items-center">
                    <a class="avatar-custom mr-3">
                        <img class="rounded" alt="..." src={{ $order->restorant->icon }}>
                    </a>
                    <div class="media-body">
                        <span class="mb-0 text-sm">{{ $order->restorant->name }}</span>
                    </div>
                </div>
            </p>
            @endif
            <p class="card-text"><strong>{{ __('Created') }}:</strong> {{ $order->created_at->locale(Config::get('app.locale'))->isoFormat('LLLL') }}</p>
            <p class="card-text"><strong>{{ __('Time Slot') }}:</strong> {{ $order->time_formated }}</p>
            <p class="card-text"><strong>{{ __('Method') }}:</strong> <span class="badge badge-primary badge-pill">{{ $order->getExpeditionType() }}</span></p>
            <p class="card-text"><strong>{{ __('Last status') }}:</strong> @include('orders.partials.laststatus')</p>
            @hasrole('admin|owner')
            <p class="card-text"><strong>{{ __('Refund') }}:</strong>
                @if ($order->status->contains(22))
                    <span class="badge badge-success badge-pill">{{ __('Refunded') }}</span>
                @elseif($order->payment_method == 'stripe' && $order->stripe_charge_id != null)
                    <form action="{{ route('admin.orders.refund', $order->id) }}" method="POST" style="display:inline;" onsubmit="refundOrder({{ $order->id }}); return false;" id="refund-form-{{ $order->id }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('Refund Order') }}</button>
                    </form>
                @else
                    <span class="badge badge-danger badge-pill">{{ __('Not Refundable') }}</span>
                @endif
            </p>
            @endif
            @hasrole('admin|owner|driver')
            <p class="card-text"><strong>{{ __('Client') }}:</strong> 
                @if ($order->client)
                    {{ $order->client->name }}
                @else
                    {{ $order->getConfig('client_name','') }}
                @endif
            </p>
            @endif
            @if(auth()->user()->hasRole('admin'))
            <p class="card-text"><strong>{{ __('Address') }}:</strong> {{ $order->address?$order->address->address:"" }}</p>
            @endif
            @if (!isset($hideAction))
                @if(auth()->user()->hasRole('owner'))
                <p class="card-text"><strong>{{ __('Items') }}:</strong> {{ count($order->items) }}</p>
                @endif
            @endif
            @hasrole('admin|owner')
            <p class="card-text"><strong>{{ __('Driver') }}:</strong> {{ !empty($order->driver->name) ? $order->driver->name : "" }}</p>
            @endif
            <p class="card-text"><strong>{{ __('Price') }}:</strong> @money( $order->order_price_with_discount, config('settings.cashier_currency'),config('settings.do_convertion'))</p>
            <p class="card-text"><strong>{{ __('Delivery') }}:</strong> @money( $order->delivery_price, config('settings.cashier_currency'),config('settings.do_convertion'))</p>
            @if (!isset($hideAction))
                @include('orders.partials.actions.table',['order' => $order ]) 
            @endif
        </div>
    </div>
    @endforeach
</div>

@section('js')
    <!-- CKEditor -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function refundOrder(orderId) {
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: '{{ __('This action is irreversible') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('Yes, refund it!') }}',
                cancelButtonText: '{{ __('No, cancel!') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('refund-form-' + orderId).submit();
                }
            });
        }
    </script>
@endsection
