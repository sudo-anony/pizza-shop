<style>

.json {
    font-family: 'Source Code Pro', monospace !important;
    font-size: 16px !important;
    
    & > {
        .json__item {
            display: block;
        }
    }
}

.json__item {
    display: block;
    margin-top: 10px;
    padding-left: 20px;
    user-select: none;
}

.json__item--collapsible {
    cursor: pointer;
    overflow: hidden;
    position: relative;
    
    &::before {
        position: absolute;
        left: 5px;
        transition: all 0.2s ease;
    }
    
    &::after {
        background-color: lightgrey;
        content: '';
        height: 100%;
        left: 9px;
        position: absolute;
        top: 26px;
        width: 1px;
    }
    
    &:hover {
        & > .json__key,
        & > .json__value {
            text-decoration: underline;
        }
    }
}


.json__toggle {
    display: none;
    
    &:checked ~ .json__item {
        display: block;
    }
}

.json__key {
    color: darkblue;
    display: inline;
    
    &::after {
        content: ': ';
    }
}

.json__value {
    display: inline;
}

.json__value--string {
    color: green;
}

.json__value--number {
    color: blue;
}

.json__value--boolean {
    color: red;
}
.table th, .table td {
        padding: 0.5rem;
        vertical-align: middle;
    }
</style>
@extends('layouts.app', ['title' => __('settings_api_logs')])

@section('content')

<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-12 mb-3 mb-md-0">
                            <h3 class="mb-0">{{ __('settings_api_logs') }}</h3>
                        </div>
                        <div class="col-md-4 col-12 text-md-right">
                            <!-- Add search input field here -->
                            <form method="GET" class="d-flex justify-content-end" action="{{ route('api.logs') }}" id="searchForm">
                                <input type="text" name="search" id="searchInput" class="form-control mr-2" placeholder="{{ __('search') }}">
                                <button type="submit" class="btn btn-primary">{{ __('search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Add logs List here --}}
                    {{-- <h3>{{ __('settings_email_logs') }}</h3>   --}}
                    <div class="logs-list">
                        <div class="table-responsive d-none d-md-block">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order id') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Endpoint') }}</th>
                                        <th>{{ __('Counter') }}</th>
                                        <th>{{ __('Broker') }}</th>
                                        <th>{{ __('Created') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="logsTableBody">

                                    @foreach($logs as $log)
                                        <tr>
                                            
                                            <td>#{{ $log->orderId }}</td>
                                            <td>{{ $log->status_code }}</td>
                                            <td>{{ $log->api_endpoint }}</td>
                                            <td>{{ $log->counter }}</td>
                                            <td>{{ $log->broker }}</td>
                                            <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($log->counter != 'none')
                                                    <button type="button" class="btn btn-primary show-content-btn" data-toggle="modal" data-target="#logModal" data-id="{{ $log->orderId }}" data-content="{{$log->request_payload}}" data-response="{{ $log->request_response }}">
                                                        {{ __('show_content') }}
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(count($logs) == 0)
                            <div class="card">
                                <div class="card-body">
                                    <p>{{ __('settings_no_logs') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="logModal" tabindex="-1" role="dialog" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <h1> {{ __('Request Response') }} </h1><span id="modalContentResponse"></span> -->
                    <h1> {{ __('Request Payload') }} </h1><span id="modalContent"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const ENT_QUOTES = 3;

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    $(document).ready(function() {
    // $(document).on('click', '.json__item--collapsible', function() {
    //     let checkbox = $(this).find('.json__toggle');
    //     checkbox.prop('checked', !checkbox.prop('checked'));
    //     updateCollapseSymbol($(this), checkbox.prop('checked'));
    // });

    // function updateCollapseSymbol(element, isExpanded) {
        // let symbol = isExpanded ? '−' : '+';
        // element.find('.collapse-icon').text(symbol);
    // }

    function jsonViewer(json, collapsible=false) {
        let TEMPLATES = {
            item: '<div class="json__item"><div class="json__key">%KEY%</div><div class="json__value json__value--%TYPE%">%VALUE%</div></div>',
            itemCollapsible: `<label class="json__item json__item--collapsible">
                <span class="collapse-icon">+</span>
                <input type="checkbox" class="json__toggle" hidden />
                <div class="json__key">%KEY%</div>
                <div class="json__value json__value--type-%TYPE%">%VALUE%</div>
                %CHILDREN%
            </label>`,
            itemCollapsibleOpen: `<label class="json__item json__item--collapsible">
                <span class="collapse-icon">−</span>
                <input type="checkbox" class="json__toggle" hidden checked />
                <div class="json__key">%KEY%</div>
                <div class="json__value json__value--type-%TYPE%">%VALUE%</div>
                %CHILDREN%
            </label>`
        };

        function createItem(key, value, type) {
            let element = TEMPLATES.item.replace('%KEY%', key);
            element = element.replace('%VALUE%', type === 'string' ? `"${value}"` : value);
            element = element.replace('%TYPE%', type);
            return element;
        }

        function createCollapsibleItem(key, value, type, children) {
            let tpl = collapsible ? 'itemCollapsibleOpen' : 'itemCollapsible';
            let element = TEMPLATES[tpl].replace('%KEY%', key);
            element = element.replace('%VALUE%', type);
            element = element.replace('%TYPE%', type);
            element = element.replace('%CHILDREN%', children);
            return element;
        }

        function handleChildren(key, value, type) {
            let html = '';
            for (let item in value) {
                html += handleItem(item, value[item]);
            }
            return createCollapsibleItem(key, value, type, html);
        }

        function handleItem(key, value) {
            return typeof value === 'object' ? handleChildren(key, value, 'object') : createItem(key, value, typeof value);
        }

        function parseObject(obj) {
            let result = '<div class="json">';
            for (let item in obj) {
                result += handleItem(item, obj[item]);
            }
            result += '</div>';
            return result;
        }

        return parseObject(json);
    }

    $(document).on('click', '.show-content-btn', function() {
        $('#logModalLabel').text($(this).data('id'));
        let json = $(this).data('content');
        let final_html = jsonViewer(json, true);
        setTimeout(function() {
            $('#modalContent').hide().html(final_html).fadeIn();
        },500)
       
        // $('#modalContentResponse').html($(this).data('response'))
    });
});


</script>
@endsection()
