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
                    <button type="button" class="btn btn-gradient-primary">Add User</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <a href="test.com">
                                    <tr>
                                        <td>
                                            {{-- <img src="{{ asset('images/icons/angular.svg') }}" class="me-75" height="20" --}}
                                            {{-- width="20" alt="Angular" /> --}}
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ config('status.type')[$user->type] }}</td>
                                        <td>
                                            <a class="" href="#">
                                                <i data-feather="edit-2" class="me-50"></i>
                                            </a>
                                            <a class="" href="#">
                                                <i data-feather="trash" class="me-50"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </a>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Hoverable rows end -->
@endsection
