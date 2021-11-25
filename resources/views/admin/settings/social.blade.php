@extends('admin.layouts.master')

@section('admin_head')
    <title>{{ hwa_page_title("Social Login") }}</title>
    <meta content="{{ hwa_page_title("Social Login") }}" name="description"/>
@endsection

@section('admin_style')

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
                            <li class="breadcrumb-item"><a href="{{ route("{$path}.index") }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Social Login</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                    </div>
                </div>
            </div>
            <!-- End information -->
        </div>
        <!-- End row -->

    </div>
@endsection

@section('admin_script')

@endsection
