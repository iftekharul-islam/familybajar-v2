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
                        <input type="text" class="form-control" id="floating-label1" placeholder="Search" />
                        <label for="floating-label1">Search by Name</label>
                    </div>
                    <a href="/settings/manual-add"><button type="button" class="btn btn-gradient-primary">Add
                            Manual</button></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Buyer</th>
                                <th>Dealer</th>
                                <th>Generation Length</th>
                                <th>Manual Length</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $user->user->name }}</span>
                                    </td>
                                    <td>{{ $user->buyer }}%</td>
                                    <td>{{ $user->dealer }}%</td>
                                    <td>{{ count($user->percentage ?? []) }}</td>
                                    <td>{{ count($user->manual ?? []) }}</td>
                                    <td>
                                        <a class="" href="{{ route('manualEdit', $user->user->id) }}">
                                            <i data-feather="edit-2" class="me-50"></i>
                                        </a>
                                        <a class="" href="#">
                                            <i data-feather="trash" class="me-50"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($users->count() == 0)
                        <div class="d-flex justify-content-center m-1">
                            <h4>No User Found</h4>
                        </div>
                    @else
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Hoverable rows end -->
@endsection
