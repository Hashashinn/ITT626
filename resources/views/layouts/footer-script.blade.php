<!-- App's Basic Js  -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
{{-- <script src="/assets/js/metisMenu.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
{{-- <script src="/assets/js/jquery.slimscroll.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.js"
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src="/assets/js/waves.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/node-waves/0.5.1/waves.min.js" crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>

@yield('script')

<!-- App js-->
<script src="/assets/js/app.js"></script>


<!-- Sweet-Alert  -->
<!-- {{-- <script src="/plugins/sweet-alert2/sweetalert2.min.js"></script> -->
<!-- <script src="/assets/pages/sweet-alert.init.js"></script>   --> --}}
{{-- <script src="/js/sweetalert.min.js"></script> --}} -->
<!-- Responsive-table-->
{{-- <script src="/plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/RWD-Table-Patterns@6.1.2/dist/js/rwd-table.min.js"></script>
<!-- Required datatable js -->
{{-- <script src="/plugins/datatables/jquery.dataTables.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
    integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

{{-- <script src="/plugins/datatables/dataTables.bootstrap4.min.js"></script> --}}
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
{{-- <script src="/plugins/datatables/dataTables.buttons.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.1/js/dataTables.buttons.min.js"></script>
{{-- <script src="/plugins/datatables/buttons.bootstrap4.min.js"></script> --}}

<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

{{-- <script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.bootstrap4.js"></script> --}}
{{-- <script src="/plugins/datatables/jszip.min.js"></script>
<script src="/plugins/datatables/pdfmake.min.js"></script>
<script src="/plugins/datatables/vfs_fonts.js"></script>
<script src="/plugins/datatables/buttons.html5.min.js"></script>
<script src="/plugins/datatables/buttons.print.min.js"></script>
<script src="/plugins/datatables/buttons.colVis.min.js"></script> --}}
<!-- Responsive examples -->
{{-- <script src="/plugins/datatables/dataTables.responsive.min.js"></script> --}}
{{-- <script src="/plugins/datatables/responsive.bootstrap4.min.js"></script> --}}
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
<!-- Datatable init js -->
<script src="/assets/pages/datatables.init.js"></script>
<script type="text/javascript">
    var require = {
        baseUrl: "/assets/js/",
        waitSeconds: 15,
        urlArgs: "v=" + Date.now()
    };
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js" data-main="controller"></script>


@yield('script-bottom')
