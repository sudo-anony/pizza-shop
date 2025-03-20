@extends('layouts.app', ['title' => __('settings_email_logs')])

@section('content')
<style>
    .table th, .table td {
        padding: 0.5rem;
        vertical-align: middle;
    }
</style>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-md-8 col-12 mb-3 mb-md-0">
                            <h3 class="mb-0">{{ __('settings_email_logs') }}</h3>
                        </div>
                        <div class="col-md-4 col-12 text-md-right">
                            <!-- Add search input field here -->
                            <form method="GET" class="d-flex justify-content-end" action="{{ route('email.logs') }}" id="searchForm">
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
                                        <th>{{ __('date') }}</th>
                                        <th>{{ __('from') }}</th>
                                        <th>{{ __('receiver') }}</th>
                                        <th>{{ __('subject') }}</th>
                                        <th>{{ __('actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="logsTableBody">
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                            <td>{{ $log->restaurant_name ?? __('unknown') }}</td>
                                            <td>{{ $log->receiver }}</td>
                                            <td>{{ $log->subject }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary show-content-btn" data-toggle="modal" data-target="#logModal" data-receiver="{{ $log->receiver }}" data-subject="{{ $log->subject }}" data-content="{!! htmlspecialchars($log->content, ENT_QUOTES, 'UTF-8') !!}" data-created_at="{{ $log->created_at->format('d.m.Y H:i') }}">
                                                    {{ __('show_content') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-md-none">
                            @foreach($logs as $log)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-column">
                                                <h3 class="card-title">{{ $log->restaurant_name ?? __('unknown') }}</h3>
                                                <h3 class="card-title">{{ $log->receiver }}</h3>
                                                <h5 class="card-subtitle mb-2 text-muted">{{ $log->subject }}</h5>
                                            </div>
                                            <button type="button" class="btn btn-primary show-content-btn" data-toggle="modal" data-target="#logModal" data-receiver="{{ $log->receiver }}" data-subject="{{ $log->subject }}" data-content="{{ htmlspecialchars($log->content, ENT_QUOTES, 'UTF-8') }}" data-created_at="{{ $log->created_at->format('d.m.Y H:i') }}">
                                                {{ __('show_content') }}
                                            </button>
                                        </div>
                                        <p class="card-text">{{ $log->created_at->format('d.m.Y H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(count($logs) == 0)
                            <div class="card">
                                <div class="card-body">
                                    <p>{{ __('settings_no_logs') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Add pagination links -->
                    {{-- <div class="d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div> --}}
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
                    <p><strong>{{ __('receiver') }}:</strong> <span id="modalReceiver"></span></p>
                    <p><strong>{{ __('subject') }}:</strong> <span id="modalSubject"></span></p>
                    <p id="modalContent"></p>
                    <p><strong>{{ __('created_at') }}:</strong> <span id="modalCreatedAt"></span></p>
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
        $('#searchInput').on('keyup', debounce(function() {
            var query = $(this).val();
            $.ajax({
                url: "{{ route('email.logs.search') }}",
                type: "GET",
                data: {'search': query},
                success: function(data) {
                    // Update table body
                    let tableBodyHtml = '';
                    data.logs.forEach(log => {
                        tableBodyHtml += `
                            <tr>
                                <td>${(function(){
                                    const date = new Date(log.created_at);
                                    const day = ("0" + date.getDate()).slice(-2);
                                    const month = ("0" + (date.getMonth() + 1)).slice(-2);
                                    const year = date.getFullYear().toString();
                                    const hours = ("0" + date.getHours()).slice(-2);
                                    const minutes = ("0" + date.getMinutes()).slice(-2);
                                    return `${day}.${month}.${year} ${hours}:${minutes}`;
                                })()}</td>
                                <td>${log.restaurant_name ?? '{{ __('unknown') }}'}</td>
                                <td>${log.receiver}</td>
                                <td>${log.subject}</td>
                                <td>
                                    <button type="button" class="btn btn-primary show-content-btn" data-toggle="modal" data-target="#logModal" data-receiver="${log.receiver}" data-subject="${log.subject}" data-content="${htmlspecialchars(log.content, ENT_QUOTES, 'UTF-8')}" data-created_at="${log.created_at}">
                                        {{ __('show_content') }}
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#logsTableBody').html(tableBodyHtml);

                    // Update mobile view
                    let mobileViewHtml = '';
                    data.logs.forEach(log => {
                        mobileViewHtml += `
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <h3 class="card-title">${log.restaurant_name ?? '{{ __('unknown') }}'}</h3>
                                            <h3 class="card-title">${log.receiver}</h3>
                                            <h5 class="card-subtitle mb-2 text-muted">${log.subject}</h5>
                                        </div>
                                        <button type="button" class="btn btn-primary show-content-btn" data-toggle="modal" data-target="#logModal" data-receiver="${log.receiver}" data-subject="${log.subject}" data-content="${htmlspecialchars(log.content, ENT_QUOTES, 'UTF-8')}" data-created_at="${log.created_at}">
                                            {{ __('show_content') }}
                                        </button>
                                    </div>
                                    <p class="card-text">${(function(){
                                        const date = new Date(log.created_at);
                                        const day = ("0" + date.getDate()).slice(-2);
                                        const month = ("0" + (date.getMonth() + 1)).slice(-2);
                                        const year = date.getFullYear().toString();
                                        const hours = ("0" + date.getHours()).slice(-2);
                                        const minutes = ("0" + date.getMinutes()).slice(-2);
                                        return `${day}.${month}.${year} ${hours}:${minutes}`;
                                    })()}</p>
                                </div>
                            </div>
                        `;
                    });
                    $('.logs-list .d-md-none').html(mobileViewHtml);
                }
            });
        }, 300));

        $(document).on('click', '.show-content-btn', function() {
            var receiver = $(this).data('receiver');
            var subject = $(this).data('subject');
            var content = $(this).data('content');
            var createdAt = $(this).data('created_at');

            $('#logModalLabel').text(subject);
            $('#modalReceiver').text(receiver);
            $('#modalSubject').text(subject);
            $('#modalContent').html(content);
            $('#modalCreatedAt').text(createdAt);
        });

        function htmlspecialchars(string, quote_style, charset, double_encode) {
            var optTemp = 0,
                i = 0,
                noquotes = false;
            if (typeof quote_style === 'undefined' || quote_style === null) {
                quote_style = 2;
            }
            string = string.toString();
            if (double_encode !== false) {
                string = string.replace(/&/g, '&amp;');
            }
            string = string.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            var OPTS = {
                'ENT_NOQUOTES': 0,
                'ENT_HTML_QUOTE_SINGLE': 1,
                'ENT_HTML_QUOTE_DOUBLE': 2,
                'ENT_COMPAT': 2,
                'ENT_QUOTES': 3,
                'ENT_IGNORE': 4
            };
            if (quote_style === 0) {
                noquotes = true;
            }
            if (typeof quote_style !== 'number') {
                quote_style = [].concat(quote_style);
                for (i = 0; i < quote_style.length; i++) {
                    if (OPTS[quote_style[i]] === 0) {
                        noquotes = true;
                    } else if (OPTS[quote_style[i]]) {
                        optTemp = optTemp | OPTS[quote_style[i]];
                    }
                }
                quote_style = optTemp;
            }
            if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
                string = string.replace(/'/g, '&#039;');
            }
            if (!noquotes) {
                string = string.replace(/"/g, '&quot;');
            }
            return string;
        }
    });
</script>
@endsection()
