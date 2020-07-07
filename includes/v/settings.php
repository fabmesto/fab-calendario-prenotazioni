<div class="wrap">
    <h1>Calendario prenotazioni</h1>

    <?php settings_errors(); ?>

    <form method="post" action="options.php">
        <?php settings_fields('fabcalpre-options');
        do_settings_sections('fabcalpre-options');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Ora dopo la quale la prenotazione attiva nello stesso giorno non viene più considerata (19)</th>
                <td>
                    <input type="text" name="fabcalpre-ora-prenotazione-attiva" value="<?php echo esc_attr(get_option('fabcalpre-ora-prenotazione-attiva', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Data prenotazione massima (AAAA-MM-GG)</th>
                <td>
                    <input type="text" name="fabcalpre-max-date" value="<?php echo esc_attr(get_option('fabcalpre-max-date', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Data prenotazione minima (AAAA-MM-GG)</th>
                <td>
                    <input type="text" name="fabcalpre-min-date" value="<?php echo esc_attr(get_option('fabcalpre-min-date', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Email oggetto (nuova prenotazione)</th>
                <td>
                    <input type="text" name="fabcalpre-email-subject-new" value="<?php echo esc_attr(get_option('fabcalpre-email-subject-new', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Email messaggio (nuova prenotazione)</th>
                <td><?php wp_editor(get_option('fabcalpre-email-message-new', ''), 'fabcalpre-email-message-new'); ?></td>
            </tr>

            <tr valign="top">
                <th scope="row">Email oggetto (prenotazione eliminata)</th>
                <td>
                    <input type="text" name="fabcalpre-email-subject-del" value="<?php echo esc_attr(get_option('fabcalpre-email-subject-del', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Email messaggio (prenotazione eliminata)</th>
                <td><?php wp_editor(get_option('fabcalpre-email-message-del', ''), 'fabcalpre-email-message-del'); ?></td>
            </tr>

            <tr valign="top">
                <th scope="row">Titolo pdf</th>
                <td>
                    <input type="text" name="fabcalpre-pdf-title" value="<?php echo esc_attr(get_option('fabcalpre-pdf-title', '')); ?>" style="width:100%" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Email messaggio (prenotazione eliminata)</th>
                <td><?php wp_editor(get_option('fabcalpre-pdf-html', ''), 'fabcalpre-pdf-html'); ?></td>
            </tr>
            <tr valign="top">
                <th scope="row">Licenza</th>
                <td><input type="text" name="<?php echo $this->parent->shortcode_admin->macaddress_name ?>" value="<?php echo esc_attr(get_option($this->parent->shortcode_admin->macaddress_name)); ?>" style="width:100%" /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <div>
        Codice da comunicare a TELNET:<br />
        <input type="text" value="<?php echo $this->parent->shortcode_admin->internal_code() ?>" style="color:#999; background-color:#ccc; width:100%" />
    </div>
</div>