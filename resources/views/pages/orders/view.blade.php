@extends('layouts/contentLayoutMaster')

@section('title', 'Order Details')

@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Details</h4>
                </div>
                <div class="card-body">
                    <div class="row my-1">
                        <div class="col-5">Order ID
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $order->id }}</div>
                    </div>
                    <div class="row my-1">
                        <div class="col-5">
                            Seller
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $order->seller->id }} - {{ $order->seller->name }}</div>
                    </div>
                    <div class="row my-1">
                        <div class="col-5">
                            Customer
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $order->customer->id }} - {{ $order->customer->name }}</div>
                    </div>
                    <div class="row my-1">
                        <div class="col-5">
                            Total Amount
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-6">BDT : {{ $order->total_price }} ৳</div>
                    </div>
                    <div class="row my-1">
                        <div class="col-5">
                            Repurchase Amount
                        </div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $order->repurchase_price }} ৳</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Repurchase Details</h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Percentage</th>
                                <th>Generation/Manual</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->repurchase_history as $repurchase)
                                <tr>
                                    <td>
                                        {{ $repurchase->user->name }} <br />
                                        <small>{{ $repurchase->user->email }}</small>
                                    </td>
                                    <td><strong>BDT : {{ $repurchase->amount }} ৳</strong></td>
                                    <td>{{ $repurchase->percentage }} %</td>
                                    <td>{{ $repurchase->is_heirarchy ? 'Generation - ' . $repurchase->chain_serial : 'Manual' }}
                                    </td>
                                    <td>
                                        @if ($repurchase->remarks)
                                            <div class="d-flex justify-content-center" title={{ $repurchase->remarks }}
                                                data-bs-toggle="tooltip" data-bs-placement="left">
                                                <i data-feather="info" class="me-50"></i>
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center">
                                                --
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th>{{ $order->repurchase_history->sum('amount') }} ৳</th>
                                <th>{{ $order->repurchase_history->sum('percentage') }} %</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Hoverable rows end -->
@endsection


@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/components/components-tooltips.js')) }}"></script>
@endsection
