<?php if (count($this->data['rows']) > 0) : ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th></th>
                <?php if ($this->can_delete()) : ?>
                    <th></th>
                <?php endif ?>
                <th>ID</th>
                <th>Risorsa</th>
                <th>Utente</th>
                <th>Data</th>
                <th>Confermata</th>
                <th>Annullata</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data['rows'] as $row) :
                $user_info = get_userdata($row['id_user']);
            ?>
                <tr class="<?php echo ($row['deleted'] == '1' ? 'bg-danger text-white' : '') ?>">
                    <td>
                        <a class="btn btn-primary btn-sm" href="javascript:;" onclick="conferma_prenotazione(<?php echo $row['id'] ?>)">
                            <i class="fas fa-calendar-check"></i>
                        </a>
                    </td>
                    <?php if ($this->can_delete()) : ?>
                        <td>
                            <a class="btn btn-danger btn-sm" href="<?php echo $this->url_delete($row['id']); ?>" onclick="return confirm('Sei sicuro?')">
                                <span class="fa fa-trash" aria-hidden="true"></span>
                            </a>
                        </td>
                    <?php endif; ?>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['risorsa']['nome'] ?></td>
                    <td>
                        <div><?php echo $user_info->first_name ?>
                            <?php echo $user_info->last_name ?>
                        </div>
                        <small>
                            <?php echo $row['user']['user_email'] ?>
                        </small>
                    </td>
                    <td><?php echo $row['data_inizio'] ?></td>
                    <td class="text-center <?php echo ($row['confirmed'] == '1' ? 'bg-green' : '') ?>"><?php echo ($row['confirmed'] == '1' ? 'SI' : 'NO') ?></td>
                    <td class="text-center"><?php echo ($row['deleted'] == '1' ? 'SI' : 'NO') ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Non ci sono righe</p>
<?php endif; ?>