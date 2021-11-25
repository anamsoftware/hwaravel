@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Users") }}</title>
    <meta content="{{ hwa_page_title("Users") }}" name="description"/>
@endsection

@section('admin_style')
    @include('admin.includes.database.style')
@endsection

@section('admin_content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><span><i
                                            class="bx bxs-home-circle"></i> Dashboard</span></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Administration</li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <div class="page-title-right">
                                <h4 class="card-title">Users</h4>
                            </div>
                            <a class="btn btn-primary" href="{{ route("{$path}.create") }}">
                                <i class="mdi mdi-plus me-2"></i> Add new
                            </a>
                        </div>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                               style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th class="text-center align-middle">Full name</th>
                                <th class="text-center align-middle">Username</th>
                                <th class="text-center align-middle">Email</th>
                                <th class="text-center align-middle">Created at</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle">Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="align-middle">{{ $result['full_name'] ?? "" }}</td>
                                    <td class="align-middle">
                                        <a href="{{ route("{$path}.edit", $result['id']) }}">
                                            {{ $result['username'] }}
                                        </a>
                                    </td>
                                    <td class="align-middle">{{ $result['email'] }}</td>
                                    <td class="text-center align-middle">{{ Carbon\Carbon::parse($result['created_at'])->format('m/d/Y H:i:s') }}</td>
                                    <td class="text-center align-middle">
                                        @if($result['active'] == 1)
                                            <span class='badge badge-pill badge-soft-success font-size-11'
                                                  style='line-height: unset!important;'>Activated</span>
                                        @else
                                            <span class='badge badge-pill badge-soft-danger font-size-11'
                                                  style='line-height: unset!important;'>Deactivated</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route("{$path}.edit", $result['id']) }}"
                                           class="btn btn-primary mr-3" style="margin-right: 10px;"><i
                                                class="bx bx-pencil"></i></a>
                                        <a href="javascript:void(0)" data-id="{{ $result['id'] }}"
                                           data-message="Do you really want to delete this record?"
                                           data-url="{{ route("{$path}.destroy", $result['id']) }}"
                                           class="btn btn-danger delete" data-bs-toggle="modal"
                                           data-bs-target=".deleteModal"><i class="bx bx-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>
@endsection

@section('admin_script')
    @include('admin.includes.database.script')
@endsection
