<?php if ($this->data['users']->total_users > 0) : ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>E-mail</th>
                <th>Nome e Cognome</th>
                <th>Ruolo</th>
                <th>Registrato</th>
                <th>Stato</th>
                <th>Bloccato</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data['users']->results as $row) :
                    //$user_info = get_userdata($row->ID);
                    ?>
                <tr>
                    <td>
                        <?php if ($this->can_delete()) : ?>
                            <a class="btn btn-primary btn-sm" href="<?php echo get_edit_user_link($row->ID) ?>">
                                <i class="fas fa-user-edit"></i>
                            </a>
                        <?php endif; ?>
                        <a class="btn btn-primary btn-sm" href="<?php echo $this->url_edit($row->ID); ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td><?php echo $row->ID ?></td>
                    <td><?php echo $row->user_email ?></td>
                    <td><?php echo $row->display_name ?></td>
                    <td><?php echo $row->roles[0] ?></td>
                    <td><?php echo \fab\functions::nice_date($row->user_registered) ?></td>
                    <td><?php echo $row->user_status ?></td>
                    <td class="<?php echo ($row->blocca_prenotazioni === '1' ? 'bg-red' : '') ?>"><?php echo ($row->blocca_prenotazioni === '1' ? 'Bloccato' : 'No') ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Non ci sono righe</p>
<?php endif; ?>