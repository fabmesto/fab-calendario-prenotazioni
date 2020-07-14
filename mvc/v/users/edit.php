<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><?php echo ucwords($this->name) ?> <small><?php echo ucwords($this->parent->current_action) ?></small></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="<?php echo $this->url_home() ?>">
                            <i class="fa fa-dashboard"></i>
                            <?php echo ucwords($this->name) ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><?php echo ucwords($this->parent->current_action) ?></a></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form method="POST" id="edit_form">
            <input type="hidden" id="primary_key" name="id_user" value="<?php echo $this->data['row']->ID ?>" />
            <input type="hidden" name="action" value="edit" />
            <div class="card">
                <div class="card-header">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo  $this->url_home() ?>"><?php echo  strtoupper($this->name) ?></a></li>
                        <li class="breadcrumb-item"><?php echo  strtoupper($this->parent->current_action) ?></a></li>
                    </ol>
                </div>

                <div class="card-body">

                    <h3><?php echo $this->data['row']->user_email ?></h3>
                    <div class="row">
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Nome", "info[first_name]", $this->data['row']->first_name) ?>
                        </div>
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Cognome", "info[last_name]", $this->data['row']->last_name) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?php echo \fab\functions::html_select_edit("Sesso", "info[gender][]", \fab\functions::options_array($this->data['row']->gender[0], array('Maschio' => 'Maschio', 'Femmina' => 'Femmina'))) ?>
                        </div>
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Nazione", "info[country]", $this->data['row']->country) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Data di nascita", "info[birth_date]", \fab\functions::date_to_ita($this->data['row']->birth_date)) ?>
                        </div>
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Lugoo di nascita", "info[luogo_nascita]", $this->data['row']->luogo_nascita) ?>
                        </div>
                    </div>
                    <?php echo \fab\functions::html_input_edit("Professione", "info[professione]", $this->data['row']->professione) ?>
                    <div class="row">
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Telefono", "info[phone_number]", $this->data['row']->phone_number) ?>
                        </div>
                        <div class="col-6">
                            <?php echo \fab\functions::html_input_edit("Cell.", "info[mobile_number]", $this->data['row']->mobile_number) ?>
                        </div>
                    </div>
                    <?php echo \fab\functions::html_input_edit("Indirizzo", "info[indirizzo]", $this->data['row']->indirizzo) ?>
                    <div class="row">
                        <div class="col-4">
                            <?php echo \fab\functions::html_input_edit("Comune", "info[comune]", $this->data['row']->comune) ?>
                        </div>
                        <div class="col-4">
                            <?php echo \fab\functions::html_input_edit("Provincia", "info[provincia]", $this->data['row']->provincia) ?>
                        </div>
                        <div class="col-4">
                            <?php echo \fab\functions::html_input_edit("CAP", "info[cap]", $this->data['row']->cap) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <?php echo \fab\functions::html_select_edit("Tipo documento", "info[documento_tipo][]", \fab\functions::options_array($this->data['row']->documento_tipo[0], array('Carta d’identità' => 'Carta d’identità', 'Patente' => 'Patente', 'Passaporto' => 'Passaporto', 'Permesso soggiorno' => 'Permesso soggiorno'))) ?>
                        </div>
                        <div class="col-4">
                            <?php echo \fab\functions::html_input_edit("Numero Documento", "info[documento_numero]", $this->data['row']->documento_numero) ?>
                        </div>
                        <div class="col-4">
                            <?php echo \fab\functions::html_input_edit("Data di rilascio documento", "info[documento_data]", \fab\functions::date_to_ita($this->data['row']->documento_data)) ?>
                        </div>
                    </div>
                    <?php echo \fab\functions::html_select_edit("Blocca l'utente, non potrà più fare prenotazioni", "info[blocca_prenotazioni]", \fab\functions::options_array($this->data['row']->blocca_prenotazioni, array('0' => 'Sbloccato, può prenotare', '1' => 'Blocca prenotazioni'))) ?>

                    <div class="ajax_message" role="alert"></div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Salva
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>


<script>
    jQuery(document).ready(function($) {
        /* FORM SUBMIT */
        $('form#edit_form').submit(function(e) {
            return $.fn.save_rest_api(this, '<?php echo $this->url_rest_save ?>', '');
        });
    });
</script>