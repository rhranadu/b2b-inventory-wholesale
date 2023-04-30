@include('component.gentelella.header')
<body class="nav-md">
<div class="container body">
    <div class="main_container">
    @include('component.gentelella.sidebar')
    @include('component.gentelella.top_bar')
        <!-- page content -->
        <div class="right_col" role="main">
            @yield('main_content')
        </div>
        <!-- /page content -->


        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>
@include('component.gentelella.footer')
<script>

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function submitAdminPanel(name)
    {
        $.post("{{ route('superadmin.admin_panel.store') }}", {name:name}, function (res) {
            if(res === 'true')
            {
                location.reload();
            }else{
                toastr.error('Not Fount')
            }
        });
    }
</script>
