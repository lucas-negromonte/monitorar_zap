<script type="text/javascript" src="<?= url("/shared/scripts/datatable.min.js") ?>"></script>
<script>
    $(function() {
        var table = $("#datatable").DataTable({
            paging: false,
            pageLength: -1,
            // lengthMenu: [
            //     [10, 25, 50, 100, -1],
            //     [10, 25, 50, 100, "All"]
            // ],
            buttons: {
                buttons: [{
                    extend: 'excel',
                    text: '<?= label("export") ?>',
                    className: 'btn-info btn-sm rounded'
                }]
            },
            dom: '<"#datatable-top.row" <".col-md-6"Bi><".col-md-6"f>>rt<"bottom"p><"clear">',
            // dom: 'Bfrtip',
            order: [],
            // order: [
            //     [2, "desc"]
            // ],
            language: {
                "url": "<?= url("/storage/translate/datatable/") . session()->user->language ?>.json"
            },
        });

        // Ajustes no DOM: https://datatables.net/examples/basic_init/dom.html
    });
</script>