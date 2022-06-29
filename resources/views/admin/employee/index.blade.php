@extends('admin.layouts.menu')
@section('menu_content')
@endsection

@extends('admin.layouts.master')
@section('back_content')

<meta name="csrf-token" content="{{ csrf_token() }}">


<script type="text/javascript">
$(function() {

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('employee.index') }}",
            data: function (d) {
                d.company_id = $('#company_id').val();
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('#company_id').change(function(){
        table.draw(true);
    });

});
</script>
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
            <div class="col-xs-12">

                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{!! $view_title !!}</h3>
                    </div>

                    <div class="box-body">

                        <div class="form-group col-md-3">
                            <label for="exampleInputEmail1">الشركات</label>
                            <select id="company_id" name="company_id" class="form-control">
                                <option value="0">اختار الشركة</option>
                                @if($result_companies)
                                @foreach($result_companies as $result_company)
                                <option value="{{$result_company->id}}">{{$result_company->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

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


                        <table class="table table-bordered data-table direction table-striped">
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>البريد الالكترونى</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>


                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



@endsection