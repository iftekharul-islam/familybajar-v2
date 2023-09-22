@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title">User List</h4>
                </div> --}}
                <div class="card-body d-flex justify-content-between">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="search_by_name" placeholder="Search by Customer Name" />
                        <label for="floating-label1">Search by Customer Name</label>
                    </div>
                    @if (Auth::user()->type == 3)
                        <a href="/withdraw-add"><button type="button" class="btn btn-gradient-primary">New
                                Withdraw</button></a>
                    @endif
                </div>
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
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
@endsection
