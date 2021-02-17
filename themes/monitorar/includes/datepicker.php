<script type="text/javascript" src="<?= url("/shared/scripts/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= url("/shared/scripts/moment.locales.min.js") ?>"></script>
<script type="text/javascript" src="<?= url("/shared/scripts/daterangepicker.min.js") ?>"></script>
<script>
    $('#daterange').on('keydown keyup', function(e) {
        return false;
    });
    var formatdatejs = '<?= label("format_date_js", false) ?>';

    function getDatapicker() {
        var date = $("#daterange").val();
        date = date.split(' <?= label("to") ?> ');

        var date_start = date[0] == "" ? "<?= date(label("format_date_php"), false) ?>" : date[0];
        var date_end = date[1] == "" ? "<?= date(label("format_date_php"), false) ?>" : date[1];

        date_start = moment(date_start, formatdatejs).format('YYYY-MM-DD');
        date_end = moment(date_end, formatdatejs).format('YYYY-MM-DD');

        $('#date_start').val(date_start);
        $('#date_end').val(date_end);
    }

    $(function() {
        var lang = "<?= session()->user->language == "pt" ? "pt-br" : session()->user->language ?>";
        moment.locale(lang); 
        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            $('#period').val(picker.chosenLabel);
            getDatapicker();
        });

        $('#daterange').daterangepicker({
            "timePickerSeconds": true,
            "autoApply": true,
            "ranges": {
                '<?= label("today") ?>': [moment(), moment()],
                '<?= label("yesterday") ?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '<?= label("last_7_days") ?>': [moment().subtract(6, 'days'), moment()],
                '<?= label("last_15_days") ?>': [moment().subtract(14, 'days'), moment()],
                '<?= label("last_30_days") ?>': [moment().subtract(29, 'days'), moment()],
                '<?= label("this_month") ?>': [moment().startOf('month'), moment().endOf('month')],
                '<?= label("last_month") ?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "locale": {
                "format": formatdatejs,
                "separator": " <?= label("to", false) ?> ",
                "applyLabel": "<?= label("apply") ?>",
                "cancelLabel": "<?= label("cancel") ?>",
                "fromLabel": "<?= label("from") ?>",
                "toLabel": "<?= label("to") ?>",
                "customRangeLabel": "<?= label("custom") ?>",
                "weekLabel": "W",
                "daysOfWeek": [
                    "<?= label("weeksu") ?>",
                    "<?= label("weekmo") ?>",
                    "<?= label("weektu") ?>",
                    "<?= label("weekwe") ?>",
                    "<?= label("weekth") ?>",
                    "<?= label("weekfr") ?>",
                    "<?= label("weeksa") ?>"
                ],
                "monthNames": [
                    "<?= label("january") ?>",
                    "<?= label("february") ?>",
                    "<?= label("march") ?>",
                    "<?= label("april") ?>",
                    "<?= label("may") ?>",
                    "<?= label("june") ?>",
                    "<?= label("july") ?>",
                    "<?= label("august") ?>",
                    "<?= label("september") ?>",
                    "<?= label("october") ?>",
                    "<?= label("november") ?>",
                    "<?= label("december") ?>"
                ],
                "firstDay": 7
            },
            "alwaysShowCalendars": true,
            "maxDate": new Date(),
            "startDate": moment($('#date_start').val(), 'YYYY-MM-DD').format(formatdatejs),
            "endDate": moment($('#date_end').val(), 'YYYY-MM-DD').format(formatdatejs),
            "opens": "left"
        });
    });
</script>