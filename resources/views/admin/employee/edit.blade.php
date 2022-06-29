@extends('admin.layouts.menu')
@section('menu_content')
@endsection

@extends('admin.layouts.master')
@section('back_content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>{!! $title !!}</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> الرئيسية</a></li>
            <li class="active">{!! $view_title !!}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{!! $view_title !!}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('employee.update',$result_page->id) }}" method="post"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="box-body">

                            @if(session('success_error'))
                            <div class="alert alert-success" role="alert">
                                {{session('success_error')}}
                            </div>
                            @endif

                            @if(session('failed_error'))
                            <div class="alert alert-danger" role="alert">
                                {{session('failed_error')}}
                            </div>
                            @endif

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"> الشركات</label>
                                <select class="form-control" name="page_company_id">
                                    @foreach($result_companies as $company)
                                    <option value="{{$company->id}}"
                                        {{$company->id == $result_page->company_id ? "selected" : ""}}>
                                        {{ $company->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">الاسم</label>
                                <input type="text" class="form-control" name="page_name" value="{{$result_page->name}}"
                                    placeholder="Name" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">البريد الالكترونى</label>
                                <input type="email" class="form-control" name="email" value="{{$result_page->email}}" placeholder="البريد الالكترونى"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">كلمة المرور</label>
                                <input type="text" class="form-control" name="page_password" placeholder="كلمة المرور"
                                    required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="exampleInputFile">الصورة</label>
                                <br>
                                @if($result_page->image != "")
                                <img src="{{url($result_page->image)}}"
                                    style="width: 100px; height: 100px;">
                                @endif
                                <br><br>
                                <input type="file" name="page_picture" style="float: right">
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

            </div>
            <!--/.col (left) -->

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection