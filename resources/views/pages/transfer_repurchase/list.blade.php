@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title">User List</h4>
                </div> --}}
                <form action="{{ route('transfer.index') }}" method="get">
                    <div class="card-body d-flex">
                        <div class="col-4">
                            @if (Auth::user()->type == config('status.type_by_name.admin'))
                                <select class="select2 form-select" id="customer_id" name="seller_id">
                                    <option value="" disabled selected>Select a Seller</option>
                                    @foreach ($sellers ?? [] as $seller)
                                        <option value="{{ $seller->id }}"
                                            {{ Request()->get('seller_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->name }} ({{ $seller->email }})
                                        </option>
                                    @endforeach
                                </select>

                            @endif
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary ml-5">Search</button>
                        </div>
                        @if (Auth::user()->type == config('status.type_by_name.seller'))
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addOrder">New Transfer Request</button>
                        @endif
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                @if (Auth::user()->type == config('status.type_by_name.admin'))
                                    <th>Dealer</th>
                                @endif
                                <th>Amount</th>
                                <th>Medium</th>
                                <th>TrxID</th>
                                <th>Created at</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $history->id }}</span>
                                    </td>
                                    @if (Auth::user()->type == config('status.type_by_name.admin'))
                                        <td>
                                            {{ $history->seller->name }}
                                            <small> {{ $history->seller->email }}</small>
                                        </td>
                                    @endif
                                    <td>BDT: <b>{{ $history->amount }} ৳</b></td>
                                    <td>{{ $history->account }}</td>
                                    <td>{{ $history->trxID }}</td>
                                    <td>{{ \Carbon\Carbon::parse($history->created_at)->format('d M Y H:ia')  }}</td>
                                    <td>
                                        @if ($history->status == 1)
                                            <span
                                                class="badge bg-info">{{ config('status.withdraw')[$history->status] }}</span>
                                        @elseif ($history->status == 2)
                                            <span
                                                class="badge bg-warning">{{ config('status.withdraw')[$history->status] }}</span>
                                        @elseif ($history->status == 3)
                                            <span
                                                class="badge bg-success">{{ config('status.withdraw')[$history->status] }}</span>
                                        @elseif ($history->status == 4)
                                            <span
                                                class="badge bg-danger">{{ config('status.withdraw')[$history->status] }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->remarks }}</td>
                                    <td>
                                        @if (Auth::user()->type == config('status.type_by_name.admin'))
                                            <a href="/transfer-repurchase-edit/{{ $history->id }}">
                                                <i data-feather="edit-2" class="me-50"></i>
                                            </a>
                                        @endif
                                        @if (Auth::user()->type == config('status.type_by_name.seller') && $history->status == 1)
                                            <a class="" href="/transfer-repurchase-cancel/{{ $history->id }}">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>
                                        @endif
                                    </td>
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

    <div class="modal fade" id="addOrder" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">New Transfer Request</h1>
                        <div>Transferable Balance : {{ Auth::user()->total_order_repurchase_amount }}</div>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('transfer.add') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="total_price">Total Amount</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" id="total_price" class="form-control"
                                                name="amount" placeholder="Total Amount" value="{{ old('amount') }}" />
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="account">Transfer Via</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="account" class="form-control" name="account"
                                                placeholder="Transfer Via" value="{{ old('account') }}" />
                                            @error('account')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="trxID">Transaction ID</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="trxID" class="form-control" name="trxID"
                                                placeholder="Transaction ID" value="{{ old('trxID') }}" />
                                            @error('trxID')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="repurchase_price">Remarks</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="remarks" class="form-control" name="remarks"
                                                placeholder="Remarks" value="{{ old('remarks') }}" />
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
