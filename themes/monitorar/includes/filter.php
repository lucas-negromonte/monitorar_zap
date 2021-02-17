<!-- Modal Filtro -->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter" aria-hidden="true">
    <div class="modal-dialog float-right h-100 w-100 m-0" role="document">
        <div class="modal-content h-100 modalFilterContent">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body  bg-white">
                <form class="ajax_off" id="formfilter" name="formfilter">
                    <?php if (!empty($filter->data)) : ?>
                        <div class="m-10-0-10">
                            <label for="data1"><?= label('start_date') ?></label>
                            <input type="date" id="data1" name="data1" value="<?= (!empty($filter->data1) ? $filter->data1 : '') ?>" class="form-control">
                        </div>
                        <div class="m-10-0-10">
                            <label for="data2"><?= label('end_date') ?></label>
                            <input type="date" id="data2" value="<?= (!empty($filter->data2) ? $filter->data2 : '') ?>" name="data2" class="form-control">
                        </div>
                    <?php endif; ?>
                    <div class="msgDates">
                        <?php if (!empty($filter->data1) && !empty($filter->data2) && date('Y-m-d', strtotime($filter->data1)) > date('Y-m-d', strtotime($filter->data2))) : ?>
                            <b class="text-danger msgdata1"><?= msg('date_invalid') ?></b>
                        <?php endif; ?>
                    </div>
                    <button class="btn w-100" id="btn-filter" style="background-color:  #075e54; color:white;margin-top: 40px;padding: 10px;"><?= label('search') ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($filter)) : ?>
    <script>
        // Mostrar o botão de filtro
        $('.div-btn-filtro').show(200);
        $('#data1').change(function() {
            validDates();
        });

        $('#data2').change(function() {
            validDates();
        });

        //Verifica se data de inicio é maior do que a data de termino
        function validDates() {
            if ($('#data1').val() != '' && $('#data2').val() != '') {
                if ($('#data1').val() > $('#data2').val()) {
                    $('.msgDates').html('<b class="text-danger msgdata1"><?= msg('date_invalid') ?> </b>');
                } else if ($('#data1').val() == $('#data2').val()) {
                    $('.msgDates').html('');
                } else {
                    $('.msgDates').html('');
                }
            } else {
                $('.msgDates').html('');
            }
        }
    </script>
<?php endif; ?>