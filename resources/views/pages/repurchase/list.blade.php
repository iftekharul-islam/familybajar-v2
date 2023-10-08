@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection


@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                @if (Auth::user()->type == config('status.type_by_name.admin'))
                    <form action="{{ route('repurchase-history') }}" method="get">
                        <div class="card-body d-flex">
                            <div class="col-4">
                                <select class="select2 form-select" id="customer_id" name="customer_id">
                                    <option value="" disabled selected>Select a User</option>
                                    @foreach ($users ?? [] as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ Request()->get('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary ml-5">Search</button>
                            </div>
                        </div>
                    </form>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Seller</th>
                                <th>Consumer</th>
                                <th>Payment Details</th>
                                <th>Remarks</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        <a class="" href="/order/{{ $history->order_id }}">
                                            {{ $history->order_id }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $history->order->customer->name }}
                                        <small>{{ $history->order->customer->email }}</small>
                                    </td>
                                    <td>
                                        {{ $history->order->seller->name }}
                                        <small>{{ $history->order->seller->email }}</small>
                                    </td>
                                    <td>
                                        {{ $history->user->name }}
                                        <small>{{ $history->user->email }}</small>
                                    </td>
                                    <td>
                                        BDT : <b>{{ $history->amount }}</b>৳
                                       <small>{{ $history->is_heirarchy ? 'Generation - ' . $history->chain_serial : 'Manual' }}</small>
                                        <br>
                                        <b>{{ $history->percentage }}%</b>
                                    </td>
                                    <td>{{ $history->remarks }}</td>
                                    <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y H:ia')  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $histories->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $histories->url($histories->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $histories->lastPage(); $i++)
                                    <li class="page-item {{ $i == $histories->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $histories->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $histories->currentPage() == $histories->lastPage() ? 'none' : '' }}"
                                        href="{{ $histories->url($histories->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hoverable rows end -->
@endsection

@section('vendor-script')
    <!-- vendor js files -->
    <script src="{{ asset(mix('vendors/js/pagination/jquery.bootpag.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pagination/jquery.twbsPagination.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
