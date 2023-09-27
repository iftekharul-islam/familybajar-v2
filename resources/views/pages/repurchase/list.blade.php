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
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Percentage</th>
                                <th>Serial</th>
                                <th>Generation/Manual</th>
                                <th>Remarks</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $history->id }}</span>
                                    </td>
                                    <td>
                                        <a class="" href="/order/{{ $history->order_id }}">
                                            {{ $history->order_id }}
                                        </a>
                                    </td>
                                    <td>{{ $history->user->name }}</td>
                                    <td>{{ $history->amount }} tk</td>
                                    <td>{{ $history->percentage }}%</td>
                                    <td>{{ $history->chain_serial }}</td>
                                    <td>{{ $history->is_heirarchy ? 'Generation' : 'Manual' }}</td>
                                    <td>{{ $history->remarks }}</td>
                                    {{-- <td>
                                        <a class="" href="/order/{{ $history->id }}">
                                            <i data-feather="eye" class="me-50"></i>
                                        </a>
                                         <a class="" href="#">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        <a class="" href="#">
                                            <i data-feather="trash" class="me-50"></i>
                                        </a>
                                    </td> --}}
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
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pagination/components-pagination.js')) }}"></script>
@endsection
