<div class="row">
    <div class="col-12">
        <form method="get" role="search" class="form-inline" autocomplete="off" action="<?php echo $this->url_home() ?>">
            <?php echo $this->inputs_hidden('home'); ?>
            <div class="form-row">
                <?php echo $this->default_forms_fields(); ?>

                <button type="submit" class="btn btn-primary">
                    <span class="fa fa-search" aria-hidden="true"></span>
                    Cerca
                </button>
            </div>
        </form>
    </div>
    <!--
    <div class="col-3 text-center">
        <a class="btn btn-success" href="<?php echo $this->url_edit(); ?>">
            <i class="fa fa-plus-circle"></i> Nuovo
        </a>
    </div>
    -->
</div>