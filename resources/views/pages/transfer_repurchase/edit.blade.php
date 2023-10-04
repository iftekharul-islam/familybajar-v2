@extends('layouts/contentLayoutMaster')

@section('title', 'Update Transfer Repurchase')

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Transfer Repurchase</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('transfer.update') }}" method="POST">
                            @csrf
                            <input hidden type="text" name="id" value="{{ $history->id }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Amount</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="first-name" class="form-control" name="amount"
                                                disabled placeholder="Total Price" value="{{ $history->amount }}" />
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="remarks">Transfer Via</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="remarks" class="form-control" name="trxID"
                                                placeholder="account" disabled value="{{ $history->account }}" />
                                            @error('account')
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
                                                placeholder="TrxID" disabled value="{{ $history->trxID }}" />
                                            @error('trxID')
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
                                            <select class="hide-search form-select" id="status" name="status"
                                                value={{ old('status') }}>
                                                <option value="1" disabled
                                                    @if ($history->status == 1) selected @endif>
                                                    Pending</option>
                                                <option value="2" disabled
                                                    @if ($history->status == 2) selected @endif>
                                                    Canceled</option>
                                                <option value="3" @if ($history->status == 2) disabled @endif
                                                    @if ($history->status == 3) selected @endif>
                                                    Confirmed</option>
                                                <option value="4" @if ($history->status == 2) disabled @endif
                                                    @if ($history->status == 4) selected @endif>
                                                    Rejected</option>
                                            </select>
                                            @error('status')
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
                                                placeholder="Remarks" value="{{ old('remarks') ?? $history->remarks }}" />
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
