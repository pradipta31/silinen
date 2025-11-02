</div>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.1
    </div>
    <strong>Copyright &copy; 2025 <a href="#">DeeptaStudy</a>.</strong> All rights
    reserved.
</footer>
<div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    <script src="../assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="../assets/plugins/morris/morris.min.js"></script>
    <script src="../assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="../assets/plugins/knob/jquery.knob.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="../assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="../assets/plugins/fastclick/fastclick.min.js"></script>
    <script src="../assets/dist/js/app.min.js"></script>
    <script src="../assets/dist/js/pages/dashboard.js"></script>
    <script src="../assets/dist/js/demo.js"></script>
    <?php if(!empty($additionalJS)): ?>
        <?php foreach($additionalJS as $js): ?>
            <script src="<?= htmlspecialchars($js) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($inlineJS)) echo $inlineJS; ?>
</body>

</html>