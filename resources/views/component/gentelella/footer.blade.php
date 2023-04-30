
<!-- jQuery -->
<script src="{{ asset('backend/gentelella_template/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('backend/gentelella_template/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('backend/gentelella_template/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('backend/gentelella_template/vendors/nprogress/nprogress.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('backend/gentelella_template/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- gauge.js -->
<script src="{{ asset('backend/gentelella_template/vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('backend/gentelella_template/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('backend/gentelella_template/vendors/iCheck/icheck.min.js') }}"></script>
<!-- Skycons -->
<script src="{{ asset('backend/gentelella_template/vendors/skycons/skycons.js') }}"></script>

<!-- Flot -->
<script src="{{ asset('backend/gentelella_template/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('backend/gentelella_template/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<!-- DateJS -->
<script src="{{ asset('backend/gentelella_template/vendors/DateJS/build/date.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('backend/gentelella_template/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('backend/gentelella_template/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('backend/gentelella_template/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

@stack('script')


<!-- Custom Theme Scripts -->
<script src="{{ asset('backend/gentelella_template/build/js/custom.min.js') }}"></script>

<!-- Data Table JS
    ============================================ -->
<script src="{{ asset('backend/js/data-table/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/js/data-table/data-table-act.js') }}"></script>

<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2@8.js') }}"></script>

<script>
    var modal = document.getElementById('id01');
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
