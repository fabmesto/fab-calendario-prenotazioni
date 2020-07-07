<?php
$page_args = array(
    'base' => add_query_arg('pag', '%#%'),
    'format' => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total' => ceil($this->data['users']->total_users/ $this->params['paging'] ),
    'current' => $this->params['pag'],
    'type' => 'array'
);

$pagination_array = paginate_links($page_args);
if (is_array($pagination_array)) {
    $this->pagination_html = '<div class="row">';
    $this->pagination_html .= '<div class="col-4">Righe: <b>' . $this->data['users']->total_users . '</b></div>';
    $this->pagination_html .= '<div class="col-8 text-right">';
    $this->pagination_html .= '<ul class="fab-pagination pagination">';
    foreach ($pagination_array as $page) {
        $this->pagination_html .= '<li>' . $page . '</li>';
    }
    $this->pagination_html .= '</ul>';
    $this->pagination_html .= '</div>';
    $this->pagination_html .= '</div>';
    echo $this->pagination_html;
}