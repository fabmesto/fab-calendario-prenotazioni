<?php echo $this->{$this->default_model_name}->pagination_html ?>

<div class="text-center">
    <a class="btn btn-secondary" href="<?php echo $this->url_ajax_by_action('ajax_csv', \fab\functions::get_in_array(array($this->parent->controller_name, $this->parent->action_name))) ?>">
        <i class="fas fa-file-csv"></i>
        Esporta in CSV
    </a>
</div>