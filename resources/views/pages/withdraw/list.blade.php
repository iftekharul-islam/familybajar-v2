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
                <form action="{{ route('withdrawRequests') }}" method="get">
                    <div class="card-body d-flex justify-content-between">
                        <div class="col-6 d-flex">
                            @if (Auth::user()->type == config('status.type_by_name.admin'))
                                <select class="select2 form-select" id="customer_id" name="customer_id">
                                    <option value="" disabled selected>Select a User</option>
                                    @foreach ($users ?? [] as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ Request()->get('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary ml-5">Search</button>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addWithdraw">New
                            Withdraw</button>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>TrxID</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdraws as $withdraw)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $withdraw->id }}</span>
                                    </td>
                                    <td>{{ $withdraw->user->name }}</td>
                                    <td>
                                        Total : {{ $withdraw->amount }} tk
                                        <br />
                                        Charge : {{ $withdraw->company_charge }} tk
                                        <br />
                                        Withdraw : {{ $withdraw->withdrawable_amount }} tk

                                    </td>
                                    <td>
                                        @if ($withdraw->status == 1)
                                            <span
                                                class="badge bg-info">{{ config('status.withdraw')[$withdraw->status] }}</span>
                                        @elseif ($withdraw->status == 2)
                                            <span
                                                class="badge bg-warning">{{ config('status.withdraw')[$withdraw->status] }}</span>
                                        @elseif ($withdraw->status == 3)
                                            <span
                                                class="badge bg-success">{{ config('status.withdraw')[$withdraw->status] }}</span>
                                        @elseif ($withdraw->status == 4)
                                            <span
                                                class="badge bg-danger">{{ config('status.withdraw')[$withdraw->status] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $withdraw->trxID ?? '--' }}</td>
                                    <td>{{ $withdraw->remarks ?? 'N/A' }}</td>
                                    <td>
                                        @if (Auth::user()->type == 3 && $withdraw->status == 1)
                                            <a class="" href="/withdraw-cancel/{{ $withdraw->id }}">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>
                                        @elseif (Auth::user()->type == 1)
                                            <a class="" href="/withdraw-request-edit/{{ $withdraw->id }}">
                                                <i data-feather="edit" class="me-50"></i>
                                            </a>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($withdraws))
                        <div class="mx-1 d-flex justify-content-end">
                            <nav aria-label="Page navigation">
                                <ul class="pagination mt-2">
                                    <li class="page-item prev"><a class="page-link"
                                            style="pointer-events: {{ $withdraws->currentPage() == 1 ? 'none' : '' }}"
                                            href="{{ $withdraws->url($withdraws->currentPage() - 1) }}"></a>
                                    </li>
                                    @for ($i = 1; $i <= $withdraws->lastPage(); $i++)
                                        <li class="page-item {{ $i == $withdraws->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $withdraws->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item next" disabled><a class="page-link"
                                            style="pointer-events: {{ $withdraws->currentPage() == $withdraws->lastPage() ? 'none' : '' }}"
                                            href="{{ $withdraws->url($withdraws->currentPage() + 1) }}"></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Hoverable rows end -->
    <div class="modal fade" id="addWithdraw" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="card-title">Available Balance : {{ Auth::user()->total_amount }}</h1>
                        <div>Minimum Withdraw Balance : {{ $withdraw_settings->minimum_withdraw_amount }}</div>
                        <div>Company Charge : {{ $withdraw_settings->company_charge }}%</div>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('withdrawAddButton') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label" for="withdraw-amount">Withdraw Amount</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" id="withdraw-amount" class="form-control"
                                                name="withdraw_amount" placeholder="Total Price"
                                                value="{{ old('withdraw_amount') }}" />
                                            @error('withdraw_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label" for="company-charge">Company charge
                                                ({{ $withdraw_settings->company_charge }}%)</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" id="company-charge" class="form-control"
                                                name="company_charge" placeholder="Company charge" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-4">
                                            <label class="col-form-label" for="withdrawable-amount">Withdrawable
                                                Amount</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="number" id="withdrawable-amount" class="form-control"
                                                name="withdrawable_amount" placeholder="Withdrawable Amount" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    <script>
        $(document).ready(function() {
            $('#withdraw-amount').on('input', function() {
                var withdrawAmount = parseFloat($(this).val());
                if (!isNaN(withdrawAmount)) {
                    var companyCharge = withdrawAmount * 0.15;
                    var withdrawableAmount = withdrawAmount * (1 - 0.15);

                    $('#company-charge').val(companyCharge.toFixed(2));
                    $('#withdrawable-amount').val(withdrawableAmount.toFixed(2));
                } else {
                    // Clear the values if the input is not a valid number
                    $('#company-charge').val('');
                    $('#withdrawable-amount').val('');
                }
            });
        });
    </script>
@endsection
