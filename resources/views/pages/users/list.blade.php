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
                {{-- <div class="card-header">
                    <h4 class="card-title">User List</h4>
                </div> --}}
                <div class="card-body">
                    <form action="{{ route('users') }}" method="get">
                        <div class="d-flex justify-content-between">
                            <div class="col-6 d-flex">
                                <input type="text" class="form-control" id="floating-label1"
                                       placeholder="Search By name or email" name="search"
                                       value="{{ Request()->get('search') }}" />
                                <div class="col-8 d-flex">
                                    <select class="select2 form-select" id="user_type" name="user_type">
                                        <option value="" disabled selected>Select a User type</option>
                                        <option value="3" {{ Request()->get('user_type') == 3 ? 'selected' : '' }}>Customer</option>
                                        <option value="2" {{ Request()->get('user_type') == 2 ? 'selected' : '' }}>Seller</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary ml-5">Search</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editUser">Add User</button>
                        </div>
                    </form>
{{--                    <form action="{{ route('users') }}" method="get">--}}
{{--                        <div class="form-floating">--}}
{{--                            <input type="text" class="form-control" id="floating-label1"--}}
{{--                                placeholder="Search By name or email" name="search"--}}
{{--                                value="{{ Request()->get('search') }}" />--}}
{{--                            <label for="floating-label1">Search here</label>--}}
{{--                            <select class="select2 form-select" id="customer_id" name="user_type">--}}
{{--                                <option value="" disabled selected>Select a User type</option>--}}
{{--                                    <option value="1">Customer</option>--}}
{{--                                    <option value="2">Dealer</option>--}}
{{--                            </select>--}}
{{--                            <button type="submit" class="btn btn-primary ml-5">Search</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}

                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Wallet amount</th>
                                <th>Referred By</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{-- <img src="{{ asset('images/icons/angular.svg') }}" class="me-75" height="20" --}}
                                        {{-- width="20" alt="Angular" /> --}}
                                        <span class="fw-bold">
                                            {{ $user->name }}
                                        </span><br>
                                        <small>{{ $user->email }}</small><br>
                                        <small class="badge bg-info">{{ $user->ref_code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-glow bg-primary">{{ config('status.type')[$user->type] }}</span></td>
                                    <td>
                                        <span>Repurchase amount BDT: {{ $user->repurchase_amount }} ৳</span> <br>
                                        <span>Withdraw amount BDT: {{ $user->withdraw_amount }} ৳</span> <br>
                                        <span>Total amount BDT: {{ $user->total_amount }} ৳</span>
                                    </td>
                                    <td>
                                        @if(!empty($user->refer->name))
                                            <span>{{ $user->refer->name }}</span><br>
                                            <small>{{ $user->refer->email }}</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:ia') }}</td>
                                    <td>
                                        @auth
                                            @if (Auth::user()->type == 1)
                                                <a class="" href="{{ route('loginAsUser', $user->id) }}">
                                                    <i data-feather='log-in' class="me-50"></i>
                                                </a>
                                                <a class="" href="{{ route('user.edit', $user->id) }}">
                                                    <i data-feather="edit-2" class="me-50"></i>
                                                </a>
                                            @endif
                                        @endauth
                                        <a class="" href="{{ route('user.show', $user->id) }}">
                                            <i data-feather="eye" class="me-50"></i>
                                        </a>

                                        {{--                                        <a class="" href="#"> --}}
                                        {{--                                            <i data-feather="trash" class="me-50"></i> --}}
                                        {{--                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $users->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $users->currentPage() == $users->lastPage() ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add new user</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('userAddButton') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="name"
                                                placeholder="Name" value="{{ old('name') }}" />
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="email-id">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="email" id="email-id" class="form-control" name="email"
                                                placeholder="Email" value="{{ old('email') }}" />
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="type">Type</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="hide-search form-select" id="select2-hide-search"
                                                name="type" value={{ old('type') }}>
                                                <option value="1">Admin</option>
                                                <option value="2">Seller</option>
                                                <option value="3" selected>Customer</option>
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
                                            <label class="col-form-label" for="ref_by">Refer By</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="customer_id" name="ref_by">
                                                <option value="" disabled selected>Select a User</option>
                                                @foreach ($userList ?? [] as $customer)
                                                    <option value="{{ $customer->ref_code }}"
                                                        {{ Request()->get('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="login-password"
                                                    type="password" name="password" placeholder="············"
                                                    aria-describedby="login-password" tabindex="2" />
                                                <span class="input-group-text cursor-pointer"><i
                                                        data-feather="eye"></i></span>
                                            </div>
                                            @error('password')
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
