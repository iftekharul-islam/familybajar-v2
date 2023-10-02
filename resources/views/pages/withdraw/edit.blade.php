@extends('layouts/contentLayoutMaster')

@section('title', 'New Order')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Withdraw</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('withdrawRequestEditButton') }}" method="POST">
                            @csrf
                            <input hidden type="text" name="id" value="{{ $withdraw->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Amount</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="first-name" class="form-control" name="amount"
                                                disabled placeholder="Total Price" value="{{ $withdraw->amount }}" />
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Company Charge</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="first-name" class="form-control" name="amount"
                                                disabled placeholder="Total Price"
                                                value="{{ $withdraw->company_charge }}" />
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Actual Withdraw</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="first-name" class="form-control" name="amount"
                                                disabled placeholder="Total Price"
                                                value="{{ $withdraw->withdrawable_amount }}" />
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="status">Status</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="hide-search form-select" id="select2-hide-search" name="status"
                                                value={{ old('status') }}>
                                                <option value="1" disabled
                                                    @if ($withdraw->status == 1) selected @endif>
                                                    Pending</option>
                                                <option value="2" disabled
                                                    @if ($withdraw->status == 2) selected @endif>
                                                    Canceled</option>
                                                <option value="3" @if ($withdraw->status == 2) disabled @endif
                                                    @if ($withdraw->status == 3) selected @endif>
                                                    Confirmed</option>
                                                <option value="4" @if ($withdraw->status == 2) disabled @endif
                                                    @if ($withdraw->status == 4) selected @endif>
                                                    Rejected</option>
                                            </select>
                                            @error('type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="remarks">TrxID</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="remarks" class="form-control" name="trxID"
                                                placeholder="TrxID" value="{{ old('trxID') ?? $withdraw->trxID }}" />
                                            @error('trxID')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="remarks">Remarks</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="remarks" class="form-control" name="remarks"
                                                placeholder="Remarks" value="{{ old('remarks') ?? $withdraw->remarks }}" />
                                            @error('remarks')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary me-1">Update</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
@endsection
