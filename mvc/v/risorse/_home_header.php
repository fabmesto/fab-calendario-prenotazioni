<div class="row">
    <div class="col-xs-9">
        <form method="get" role="search" class="form-inline" action="<?php echo $this->url_home() ?>">
            <?php echo $this->inputs_hidden('home'); ?>

            <?php echo $this->default_forms_fields(); ?>

            <button type="submit" class="btn btn-primary">
                <span class="fa fa-search" aria-hidden="true"></span>
                Cerca
            </button>
        </form>
    </div>
    <div class="col-xs-3 text-center">
        <a class="btn btn-success" href="<?php echo $this->url_edit(); ?>">
            <i class="fa fa-plus-circle"></i> Nuovo
        </a>
    </div>
</div>