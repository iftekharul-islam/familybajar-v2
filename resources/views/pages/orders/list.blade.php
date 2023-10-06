@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title">User List</h4>
                </div> --}}
                @if (Auth::user()->type == config('status.type_by_name.admin') ||
                        Auth::user()->type == config('status.type_by_name.seller'))
                    <form action="{{ route('orders') }}" method="get">
                        <div class="card-body d-flex justify-content-between">
                            <div class="col-6 d-flex">
                                <select class="select2 form-select" id="customer_" name="user_id">
                                    <option value="" disabled selected>Select a User</option>
                                    @foreach ($users ?? [] as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ Request()->get('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary ml-5">Search</button>
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addOrder">Add New
                                Order</button>
                        </div>
                    </form>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Dealer</th>
                                <th>Repurchase Amount</th>
                                <th>Total Amount</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $order->id }}</span>
                                    </td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td>{{ $order->seller->name }}</td>
                                    <td>{{ $order->repurchase_price }}</td>
                                    <td>{{ $order->total_price ?? 'N/A' }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        @if (in_array(auth()->user()->type, [1, 2]))
                                            <a class="" href="{{ route('orderShow', $order->id) }}">
                                                <i data-feather="eye" class="me-50"></i>
                                            </a>
                                        @else
                                            --
                                        @endif
                                        {{-- <a class="" href="#">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        <a class="" href="#">
                                            <i data-feather="trash" class="me-50"></i>
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $orders->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $orders->url($orders->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                    <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $orders->currentPage() == $orders->lastPage() ? 'none' : '' }}"
                                        href="{{ $orders->url($orders->currentPage() + 1) }}"></a>
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
                        <h1 class="mb-1">Add new order</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('orderAddButton') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="seller_id">Seller</label>
                                        </div>
                                        <div class="col-sm-9">
                                            @if (Auth::user()->type == config('status.type_by_name.admin'))
                                                <select class="select2 form-select" id="seller_id" name="seller_id">
                                                    <option value="" disabled selected>Select a Seller</option>
                                                    @foreach ($sellers as $seller)
                                                        <option value="{{ $seller->id }}"
                                                            {{ old('seller_id') == $seller->id ? 'selected' : '' }}>
                                                            {{ $seller->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @if (Auth::user()->type == config('status.type_by_name.seller'))
                                                <select class="select2 form-select" id="seller_id" name="seller_id"
                                                    disabled>
                                                    @foreach ($sellers as $seller)
                                                        <option value="{{ $seller->id }}"
                                                            {{ Auth::user()->id == $seller->id ? 'selected' : '' }}>
                                                            {{ $seller->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="seller_id" value="{{ Auth::user()->id }}">
                                            @endif
                                            @error('seller_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="customer_id">Customer</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="customer_id" name="customer_id">
                                                <option value="" disabled selected>Select a Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="total_price">Total Price</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" id="total_price" class="form-control"
                                                name="total_price" placeholder="Total Price"
                                                value="{{ old('total_price') }}" />
                                            @error('total_price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="repurchase_price">Repurchase Price</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" id="repurchase_price"
                                                class="form-control" name="repurchase_price"
                                                placeholder="Repurchase Price" value="{{ old('repurchase_price') }}" />
                                            @error('repurchase_price')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
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
