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
            <input type="hidden" id="primary_key" name="<?php echo  $this->default_model_name . '[id]' ?>" value="<?php echo  $this->data['row']['id'] ?>" />
            <div class="card">
                <div class="card-header">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo  $this->url_home() ?>"><?php echo  strtoupper($this->name) ?></a></li>
                        <li class="breadcrumb-item"><?php echo  strtoupper($this->parent->current_action) ?></a></li>
                    </ol>
                </div>

                <div class="card-body">
                    <?php echo \fab\functions::html_input_edit("Nome", $this->default_model_name . "[nome]", $this->data['row']['nome']) ?>
                    <?php echo \fab\functions::html_input_edit("Ordinamento", $this->default_model_name . "[ordinamento]", $this->data['row']['ordinamento']) ?>
                    <?php echo \fab\functions::html_textarea_edit("Note", $this->default_model_name . "[note]", $this->data['row']['note']) ?>

                    <div class="ajax_message" role="alert"></div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <span class="fa fa-floppy-o" aria-hidden="true"></span> Salva
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