<form method="GET" id="fab-form-filter" style="margin-top:25px">
    <div class="form-row">
        <div class="form-group col">
            <div class="it-datepicker-wrapper">
                <div class="form-group">
                    <input class="form-control it-date-datepicker" id="data_prenotazione" type="text" name="data_prenotazione" value="<?php echo $this->params['data_prenotazione'] ?>" placeholder="inserisci la data in formato gg/mm/aaaa">
                    <label for="data_prenotazione">Seleziona data</label>
                </div>
            </div>
        </div>
    </div>
</form>

<h5>Disponibilità sala studio</h5>
<p>
    Di seguito lo stato dei posti nella data selezionata
</p>
<div class="prenotazioni-loading" style="display: none;">
    <div class="d-flex align-items-center">
        <strong>Caricamento...</strong>
        <div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div>
    </div>
</div>
<div class="row risorse">
    <?php foreach ($this->data['risorsa'] as $risorsa) : ?>
        <div class="col-6 col-sm-3 text-center">
            <button type="button" id="btn-risorsa-<?php echo $risorsa['id'] ?>" class="btn btn-risorsa btn-search btn-outline-secondary btn-block <?php echo $this->class_stato($this->params['data_prenotazione'], $risorsa['id']) ?>" onclick="fabcalpre.prenota(<?php echo $risorsa['id'] ?>)">
                <i class="far fa-user"></i><br>
                <?php echo $risorsa['nome'] ?>
            </button>
        </div>
    <?php endforeach; ?>
</div>
<hr>
<ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Prenotato da te
        <span class="badge badge-primary badge-pill prenotato">&nbsp;</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Libero
        <span class="badge badge-primary badge-pill libero">&nbsp;</span>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Occupato
        <span class="badge badge-primary badge-pill occupato">&nbsp;</span>
    </li>
</ul>

<script>
    var fabcalpre = {};

    jQuery(document).ready(function() {
        jQuery('.it-date-datepicker').datepicker({
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [(day != 0 && day != 6), ''];
            },
            minDate: new Date(),
            maxDate: new Date("<?php echo get_option('fabcalpre-max-date') ?>"),
            inputFormat: ["dd/MM/yyyy"],
            outputFormat: 'dd/MM/yyyy',
        });

        jQuery('#data_prenotazione').change(function(e) {
            fabcalpre.change_date();

        });
        jQuery('#fab-form-filter').submit(function(e) {
            e.preventDefault();
            fabcalpre.change_date();
        });
    });


    fabcalpre.refresh_stati = (prenotazioni) => {
        jQuery('.btn-risorsa').removeClass('occupato');
        jQuery('.btn-risorsa').removeClass('prenotato');
        jQuery('.btn-risorsa').addClass('libero');
        for (let i = 0; i < prenotazioni.length; i++) {
            let prenotazione = prenotazioni[i];
            if (prenotazione.id_user == <?php echo get_current_user_id() ?>) {
                jQuery('#btn-risorsa-' + prenotazione.id_risorsa).addClass('prenotato');
            } else {
                jQuery('#btn-risorsa-' + prenotazione.id_risorsa).addClass('occupato');
                jQuery('#btn-risorsa-' + prenotazione.id_risorsa).removeClass('libero');;
            }
        }
    }

    fabcalpre.showLoading = () => {
        jQuery(".prenotazioni-loading").css('display', 'block');
    }
    fabcalpre.hideLoading = () => {
        jQuery(".prenotazioni-loading").css('display', 'none');
    }

    fabcalpre.change_date = () => {
        jQuery(".btn-search").attr("disabled", true);
        fabcalpre.showLoading();
        let data_prenotazione = jQuery('#data_prenotazione').val();
        jQuery.ajax({
            url: '<?php echo $this->url_rest_read ?>&data_prenotazione=' + data_prenotazione,
            type: 'GET',
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest') ?>');
            },
            error: function() {
                fabcalpre.hideLoading();
                jQuery(".btn-search").attr("disabled", false);
                Swal.fire({
                    title: 'Attenzione',
                    html: 'errore comunicazione server',
                    icon: 'warning',
                });
            },
            success: function(json) {
                fabcalpre.hideLoading();
                jQuery(".btn-search").attr("disabled", false);
                if (json.code == 'ok') {
                    fabcalpre.refresh_stati(json.data);
                } else {
                    // errore
                    Swal.fire({
                        title: 'Attenzione',
                        html: json.message,
                        icon: 'warning',
                    });
                }
            }
        });
    }

    fabcalpre.prenota = (id_risorsa) => {
        let data_prenotazione = jQuery('#data_prenotazione').val();
        <?php if (is_user_logged_in()) : ?>
            if (jQuery('#btn-risorsa-' + id_risorsa).hasClass('prenotato')) {
                Swal.fire({
                    title: 'Sei sicuro di eliminare le prenotazione per il ' + data_prenotazione + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Elimina',
                    cancelButtonText: 'Annulla',
                    confirmButtonColor: 'red',
                    cancelButtonColor: '#999',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        fabcalpre.ajax_save_del(id_risorsa);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Vuoi prenotare il tuo posto per il ' + data_prenotazione + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Conferma',
                    cancelButtonText: 'Annulla',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        fabcalpre.ajax_save_new(id_risorsa);
                    }
                });
            }
        <?php else : ?>
            Swal.fire({
                title: '<strong>Registrati o Accedi</strong>',
                icon: 'info',
                html: "Per prenotare devi effettuare l'accesso",
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-sign-in" aria-hidden="true"></i> Registrati',
                cancelButtonText: '<i class="fa fa-lock" aria-hidden="true"></i> Accedi',
                cancelButtonColor: '#777',

            }).then((result) => {
                if (result.value) {
                    window.location.href = '<?php echo esc_url(wp_registration_url()); ?>';
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    window.location.href = '<?php echo esc_url(wp_login_url()); ?>';
                }
            });
        <?php endif; ?>
    }


    fabcalpre.ajax_save_del = (id_risorsa) => {
        let data_prenotazione = jQuery('#data_prenotazione').val();
        fabcalpre.showLoading();
        let data = {
            action: 'del',
            data_prenotazione: data_prenotazione,
            id_risorsa: id_risorsa
        };
        jQuery.ajax({
            url: '<?php echo $this->url_rest_save ?>',
            type: 'POST',
            data: JSON.stringify(data),
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest') ?>');
            },
            error: function() {
                fabcalpre.hideLoading();
                Swal.fire({
                    title: 'Attenzione',
                    html: 'errore comunicazione server',
                    icon: 'warning',
                });
            },
            success: function(json) {
                fabcalpre.hideLoading();
                if (json.code == 'ok') {
                    Swal.fire({
                        title: 'Eliminata',
                        html: json.message,
                        icon: 'success',
                    });
                    jQuery('#data_prenotazione').trigger("change");
                } else {
                    // errore
                    Swal.fire({
                        title: 'Attenzione',
                        html: json.message,
                        icon: 'warning',
                    });
                }
            }
        });
    }

    fabcalpre.ajax_save_new = (id_risorsa) => {
        let data_prenotazione = jQuery('#data_prenotazione').val();
        fabcalpre.showLoading();
        let data = {
            action: 'prenota',
            data_prenotazione: data_prenotazione,
            id_risorsa: id_risorsa
        };
        jQuery.ajax({
            url: '<?php echo $this->url_rest_save ?>',
            type: 'POST',
            data: JSON.stringify(data),
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest') ?>');
            },
            error: function() {
                fabcalpre.hideLoading();
                Swal.fire({
                    title: 'Attenzione',
                    html: 'Errore comunicazione server',
                    icon: 'error',
                });
            },
            success: function(json) {
                fabcalpre.hideLoading();
                if (json.code == 'ok') {
                    Swal.fire({
                        title: 'Prenotato',
                        html: json.message,
                        icon: 'success',
                    });
                    jQuery('#data_prenotazione').trigger("change");
                } else {
                    // errore
                    Swal.fire({
                        title: 'Attenzione',
                        html: json.message,
                        icon: 'warning',
                    });
                }
            }
        });
    }
</script>