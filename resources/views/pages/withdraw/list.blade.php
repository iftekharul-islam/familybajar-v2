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
                @if(Auth::user()->type == config('status.type_by_name.admin'))
                    <form action="{{ route('withdrawRequests') }}" method="get">
                        <div class="card-body d-flex">
                            <div class="col-3">
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
                            <button type="submit" class="btn btn-primary ml-5">Search</button>
                        </div>
                    </form>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                    <td>{{ $withdraw->amount }} tk</td>
                                    <td>{{ config('status.withdraw')[$withdraw->status] }}</td>
                                    <td>{{ $withdraw->trxID }}</td>
                                    <td>{{ $withdraw->Remarks ?? 'N/A' }}</td>
                                    <td>
                                        @if (Auth::user()->type == 3 && $withdraw->status == 1)
                                            <a class="" href="/withdraw-cancel/{{ $withdraw->id }}">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>
                                        @endif
                                        @if (Auth::user()->type == 1)
                                            <a class="" href="/withdraw-request-edit/{{ $withdraw->id }}">
                                                <i data-feather="edit" class="me-50"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(count($withdraws))
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
