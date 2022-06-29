<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar direction">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">



            <li class="treeview @if($dpage_id == 1) active @endif">
                <a href="#">
                    <i class="fas fa-th-large"></i> <span>الشركات </span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('company.create') }}"><i class="far fa-dot-circle"></i> اضف شركة </a></li>
                    <li><a href="{{ route('company.index') }}"><i class="far fa-dot-circle"></i> عرض شركة </a></li>
                </ul>
            </li>

            <li class="treeview @if($dpage_id == 2) active @endif">
                <a href="#">
                    <i class="fas fa-th-large"></i> <span>الموظفين </span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('employee.create') }}"><i class="far fa-dot-circle"></i> اضف موظف </a></li>
                    <li><a href="{{ route('employee.index') }}"><i class="far fa-dot-circle"></i> عرض موظف </a></li>
                </ul>
            </li>



        </ul>
    </section>
    <!-- /.sidebar -->
</aside>