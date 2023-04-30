
<!-- Start Footer area-->
<div class="footer-copyright-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="footer-copy-right">
                    <p>Copyright © 2018
                        . All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Footer area-->
<!-- jquery
    ============================================ -->
<script src="{{ asset('backend/js/vendor/jquery-1.12.4.min.js') }}"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
<!-- wow JS
    ============================================ -->
<script src="{{ asset('backend/js/wow.min.js') }}"></script>
<!-- price-slider JS
    ============================================ -->
<script src="{{ asset('backend/js/jquery-price-slider.js') }}"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="{{ asset('backend/js/owl.carousel.min.js') }}"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="{{ asset('backend/js/jquery.scrollUp.min.js') }}"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="{{ asset('backend/js/meanmenu/jquery.meanmenu.js') }}"></script>
<!-- counterup JS
    ============================================ -->
<script src="{{ asset('backend/js/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('backend/js/counterup/waypoints.min.js') }}"></script>
<script src="{{ asset('backend/js/counterup/counterup-active.js') }}"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="{{ asset('backend/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<!-- jvectormap JS
    ============================================ -->
<script src="{{ asset('backend/js/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('backend/js/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('backend/js/jvectormap/jvectormap-active.js') }}"></script>
<!-- sparkline JS
    ============================================ -->
<script src="{{ asset('backend/js/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('backend/js/sparkline/sparkline-active.js') }}"></script>
<!-- sparkline JS
    ============================================ -->
<script src="{{ asset('backend/js/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('backend/js/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('backend/js/flot/curvedLines.js') }}"></script>
<script src="{{ asset('backend/js/flot/flot-active.js') }}"></script>
<!-- knob JS
    ============================================ -->
<script src="{{ asset('backend/js/knob/jquery.knob.js') }}"></script>
<script src="{{ asset('backend/js/knob/jquery.appear.js') }}"></script>
<script src="{{ asset('backend/js/knob/knob-active.js') }}"></script>
<!--  wave JS
    ============================================ -->
<script src="{{ asset('backend/js/wave/waves.min.js') }}"></script>
<script src="{{ asset('backend/js/wave/wave-active.js') }}"></script>
<!--  todo JS
    ============================================ -->
<script src="{{ asset('backend/js/todo/jquery.todo.js') }}"></script>
<!-- plugins JS
    ============================================ -->
<script src="{{ asset('backend/js/plugins.js') }}"></script>


<!-- main JS
    ============================================ -->
<script src="{{ asset('backend/js/main.js') }}"></script>


<!-- Data Table JS
    ============================================ -->
<script src="{{ asset('backend/js/data-table/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/data-table/data-table-act.js') }}"></script>




<!-- toastr JS
    ============================================ -->
<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2@8.js') }}"></script>
<script src="{{ asset('js/helper/utility.js') }}"></script>

<script>
    var URL = '{!! url('/') !!}';
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
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
<script src="{!! asset('js/helper/utility.js') !!}"></script>
<script src="{!! asset('js/helper/ajax.js') !!}"></script>
@stack('script')

{!! Toastr::message() !!}
</body>

</html>
