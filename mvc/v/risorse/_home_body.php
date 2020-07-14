<?php if (count($this->data['rows']) > 0) : ?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <?php if ($this->can_delete()) : ?>
                    <th>

                    </th>
                <?php endif ?>
                <?php foreach ($this->data['cols'] as $col => $default_value) : ?>
                    <th>
                        <?php echo \fab\functions::clean_col_name($col) ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->data['rows'] as $row) : ?>
                <tr>
                    <?php if ($this->can_delete()) : ?>
                        <td>
                            <a class="btn btn-primary btn-sm" href="<?php echo $this->url_edit($row['id']); ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" href="<?php echo $this->url_delete($row['id']); ?>" onclick="return confirm('Sei sicuro?')">
                                <span class="fa fa-trash" aria-hidden="true"></span>
                            </a>
                        </td>
                    <?php endif; ?>
                    <?php foreach ($this->data['cols'] as $col => $default_value) : ?>
                        <td><?php echo (isset($row[$col]) ? \fab\functions::clean_col_value($col, $row[$col]) : '') ?></td>
                    <?php endforeach; ?>
                </tr>

            <?php endforeach ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Non ci sono righe</p>
<?php endif; ?>