<?php

namespace fabcalpre;

if (!class_exists('fabcalpre\prenotazioni_controller')) {
    class prenotazioni_controller extends \fab\fab_controller
    {
        public $name = 'prenotazioni';
        public $models_name = array('prenotazione', 'risorsa');
        public $url_rest_save_prenotazione = '';

        public function __construct($parent)
        {
            parent::__construct($parent);
            $this->url_rest_save = $this->parent->base_rest_save . '?' . $this->parent->namespace_name . '=' . $this->parent->NAMESPACE . '&' . $this->parent->controller_name . '=' . $this->name;
            $this->url_rest_read = $this->parent->base_rest_read . '?' . $this->parent->namespace_name . '=' . $this->parent->NAMESPACE . '&' . $this->parent->controller_name . '=' . $this->name;
        }

        public function home()
        {
            $risorsa = $this->risorsa->get_results("deleted='0'", '*', 'ordinamento ASC', 0);
            $this->data['risorsa'] = \fab\functions::arraymulti_to_keys_values($risorsa, 'id', 'nome');

            $this->prenotazione->belongs_to['user'] =
                array(
                    'table_name' => 'users',
                    'cols' => 'users.*',
                    'conditions' => '',
                    'order' => '',
                    'foreign_key' => 'id_user',
                    'primary_key' => 'ID'
                );
            parent::home();
        }

        public function front()
        {

            $this->params['data_prenotazione'] = date('d/m/Y');
            $this->data['prenotazioni'] = array();
            if (isset($_GET['data_prenotazione'])) {
                $this->params['data_prenotazione'] = $_GET['data_prenotazione'];
            }

            $this->data['risorsa'] = $this->risorsa->get_results("deleted='0'", '*', 'ordinamento, nome');
        }

        public function class_stato($data_prenotazione, $id_risorsa)
        {
            if (!isset($this->data['prenotazioni'][$data_prenotazione])) {
                $this->data['prenotazioni'][$data_prenotazione] = $this->get_prenotazioni_data($data_prenotazione);
            }

            if (count($this->data['prenotazioni'][$data_prenotazione]) > 0) {
                foreach ($this->data['prenotazioni'][$data_prenotazione] as $prenotazione) {

                    if ($prenotazione['id_risorsa'] == $id_risorsa && $prenotazione['id_user'] == get_current_user_id()) return 'prenotato';
                    if ($prenotazione['id_risorsa'] == $id_risorsa) return 'occupato';
                }
            }
            return 'libero';
        }
        public function is_libero($data_prenotazione, $id_risorsa)
        {
            if (!isset($this->data['prenotazioni'][$data_prenotazione])) {
                $this->data['prenotazioni'][$data_prenotazione] = $this->get_prenotazioni_data($data_prenotazione);
            }

            if (count($this->data['prenotazioni'][$data_prenotazione]) > 0) {
                foreach ($this->data['prenotazioni'][$data_prenotazione] as $prenotazione) {
                    if ($prenotazione['id_risorsa'] == $id_risorsa) return false;
                }
            }
            return true;
        }

        public function get_prenotazioni_data($data_prenotazione, $id_risorsa = 0)
        {
            $conditions = array();
            $conditions[] = "(data_inizio<='" . \fab\functions::date_to_sql($data_prenotazione) . "' AND data_fine>='" . \fab\functions::date_to_sql($data_prenotazione) . "')";
            $conditions[] = "deleted='0'";
            if ($id_risorsa > 0) $conditions[] = "id_risorsa='" . $id_risorsa . "'";
            return $this->prenotazione->get_results($conditions, 'id, id_user, id_risorsa, data_inizio, data_fine', 'id ASC');
        }

        public function after_prepare_filter_search($filter)
        {
            $filter['params']['data_prenotazione'] = '';

            if (isset($_GET['data_prenotazione'])) {
                $filter['params']['data_prenotazione'] = $_GET['data_prenotazione'];
            }
            if ($filter['params']['data_prenotazione'] != '') {
                if ($filter["where"] == '') $filter["where"] = "data_inizio<='" .  \fab\functions::date_to_sql($filter['params']['data_prenotazione']) . "' AND data_fine>='" . \fab\functions::date_to_sql($filter['params']['data_prenotazione']) . "'";
                else $filter["where"] .= " AND data_inizio<='" . \fab\functions::date_to_sql($filter['params']['data_prenotazione']) . "' AND data_fine>='" . \fab\functions::date_to_sql($filter['params']['data_prenotazione']) . "'";
            }

            return $filter;
        }

        public function rest_read()
        {
            $this->params['data_prenotazione'] = date('d/m/Y');
            $this->data['prenotazioni'] = array();
            if (isset($_GET['data_prenotazione'])) {
                $this->params['data_prenotazione'] = $_GET['data_prenotazione'];
            }

            $this->data['prenotazioni'] = $this->get_prenotazioni_data($this->params['data_prenotazione']);
            return array(
                'code' => 'ok',
                'message' => 'Prenotazioni del ' . $this->params['data_prenotazione'],
                'data' => $this->data['prenotazioni']
            );
        }

        public function rest_save()
        {
            $current_user = wp_get_current_user();
            if ($current_user->ID == 0) {
                return array(
                    "code" => "error",
                    "message" => 'Non hai le autorizzazioni!',
                    "data" => array('post' => $_POST)
                );
            }

            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);

            $action = '';
            if (isset($_POST['action'])) {
                $action = $_POST['action'];
            }

            switch ($action) {
                case 'confirm':
                    if (isset($_POST['id_prenotazione'])) {
                        $id_prenotazione = intval($_POST['id_prenotazione']);
                    }
                    return $this->_save_confirm_prenotazione($id_prenotazione);
                    break;
                case 'del':
                    $id_risorsa = 0;
                    $data_prenotazione = date('d/m/Y');
                    if (isset($_POST['id_risorsa'])) {
                        $id_risorsa = intval($_POST['id_risorsa']);
                    }
                    if (isset($_POST['data_prenotazione'])) {
                        $data_prenotazione = $_POST['data_prenotazione'];
                    }
                    return $this->_save_del_prenotazione($current_user->ID, $id_risorsa, $data_prenotazione);
                    break;
                case 'prenota':
                    $id_risorsa = 0;
                    $data_prenotazione = date('d/m/Y');
                    if (isset($_POST['id_risorsa'])) {
                        $id_risorsa = intval($_POST['id_risorsa']);
                    }
                    if (isset($_POST['data_prenotazione'])) {
                        $data_prenotazione = $_POST['data_prenotazione'];
                    }
                    return $this->_save_new_prenotazione($current_user->ID, $id_risorsa, $data_prenotazione);

                    break;
            }
            return array(
                'code' => 'error',
                'message' => 'no action',
                'POST' => $_POST,
            );
        }

        private function _save_confirm_prenotazione($id_prenotazione)
        {
            if (current_user_can('show_all_prenotazioni') == 1) {
                $data = array();
                $data['id'] = $id_prenotazione;
                $data['confirmed'] = 1;
                $this->prenotazione->save($data);
                return array(
                    'code' => 'ok',
                    'message' => 'Prenotazione confermata'
                );
            } else {
                return array(
                    'code' => 'error',
                    'message' => 'Non hai le autorizzazioni'
                );
            }
        }

        private function _save_del_prenotazione($id_user, $id_risorsa, $data_prenotazione)
        {
            if ($id_user > 0 && $id_risorsa > 0) {
                $conditions = array();
                $conditions[] = "(data_inizio<='" . \fab\functions::date_to_sql($data_prenotazione) . "' AND data_fine>='" . \fab\functions::date_to_sql($data_prenotazione) . "')";
                $conditions[] = "id_risorsa='" . $id_risorsa . "'";
                $conditions[] = "id_user='" . $id_user . "'";
                $conditions[] = "deleted='0'";
                $prenotazione = $this->prenotazione->get_results($conditions, '*', 'id ASC');
                if (count($prenotazione) > 0) {
                    if ($prenotazione[0]['confirmed'] == '0') {
                        $id_prenotazione = $prenotazione[0]['id'];
                        $this->prenotazione->delete_by_id($id_prenotazione);
                        $this->_send_email_del_prenotazione($id_prenotazione);
                        return array(
                            'code' => 'ok',
                            'message' => 'Prenotazione eliminata',
                            'data' => $id_prenotazione,
                        );
                    } else {
                        // prenotazione confermata
                        return array(
                            'code' => 'error',
                            'message' => 'Non è possibile eliminare una prenotazione confermata!'
                        );
                    }
                } else {
                    return array(
                        'code' => 'error',
                        'message' => 'Nessuna prenotazione trovata'
                    );
                }
            }
        }

        private function _save_new_prenotazione($id_user, $id_risorsa, $data_prenotazione)
        {
            if ($id_user > 0 && $id_risorsa > 0) {
                // weekend
                if (service::isWeekend(\fab\functions::date_to_sql($data_prenotazione))) {
                    return array(
                        'code' => 'error',
                        'message' => 'In questa data la biblioteca è chiusa'
                    );
                }
                // data nel passato
                if (\fab\functions::date_to_sql($data_prenotazione) < date('Y-m-d')) {
                    return array(
                        'code' => 'error',
                        'message' => 'Non puoi prenotare per una data passata'
                    );
                }
                // data prima dell'apertura
                $min_date = get_option('fabcalpre-min-date');
                if ($min_date != '' and \fab\functions::date_to_sql($data_prenotazione) < $min_date) {
                    return array(
                        'code' => 'error',
                        'message' => 'In questa data la biblioteca è chiusa (aperta dal ' . \fab\functions::date_to_ita($min_date) . ')'
                    );
                }
                // data oltre il massimo
                $max_date = get_option('fabcalpre-max-date');
                if ($max_date != '' and \fab\functions::date_to_sql($data_prenotazione) > $max_date) {
                    return array(
                        'code' => 'error',
                        'message' => 'In questa data la biblioteca è chiusa (aperta fino al ' . \fab\functions::date_to_ita($max_date) . ')'
                    );
                }

                $prenotazione_attiva = $this->_user_prenotazione_attiva($id_user);
                if (count($prenotazione_attiva) > 0) {
                    $prenotazione_attiva = $this->_check_user_prenotazioni_attive_oggi($prenotazione_attiva);
                    if (count($prenotazione_attiva) > 0) {
                        return array(
                            'code' => 'error',
                            'message' => 'Hai già una prenotazione attiva: ' . date('d-m-Y', strtotime(\fab\functions::date_to_sql($prenotazione_attiva[0]['data_inizio']))),
                        );
                    }
                }
                // controlla che la risorsa non sià già occupata
                $prenotazioni = $this->get_prenotazioni_data($data_prenotazione, $id_risorsa);
                if (count($prenotazioni) > 0) {
                    return array(
                        'code' => 'error',
                        'message' => 'Il posto scelto è già occupato in questa data'
                    );
                }
                // salva
                $data = array();
                $data['id'] = 0;
                $data['id_user'] = $id_user;
                $data['id_risorsa'] = $id_risorsa;
                $data['data_inizio'] = \fab\functions::date_to_sql($data_prenotazione);
                $data['data_fine'] = \fab\functions::date_to_sql($data_prenotazione) . ' 23:59:59';
                $id_prenotazione = $this->prenotazione->save($data);
                $allegato = $this->_generate_pdf($id_prenotazione);
                $this->_send_email_new_prenotazione($id_prenotazione, $allegato);
                return array(
                    'code' => 'ok',
                    'message' => "Prenotazione salvata, abbiamo inviato un'e-mail al suo indirizzo di posta",
                    'data' => $id_prenotazione,
                );
            } else {
                return array(
                    'code' => 'error',
                    'message' => 'Non loggato o nessuna risorsa selezionata'
                );
            }
        }

        private function _user_prenotazione_attiva($id_user)
        {
            $data_oggi = date('d/m/Y');
            $conditions = array();
            $conditions[] = "data_inizio>='" . \fab\functions::date_to_sql($data_oggi) . "'";
            $conditions[] = "deleted='0'";
            $conditions[] = "id_user='" . $id_user . "'";
            $prenotazioni = $this->prenotazione->get_results($conditions, '*', 'id ASC');
            return $prenotazioni;
        }

        private function _check_user_prenotazioni_attive_oggi($prenotazioni)
        {
            date_default_timezone_set('Europe/Rome');
            $data_oggi = date('d/m/Y');
            $ora_prenotazione_attiva = get_option('fabcalpre-ora-prenotazione-attiva', '');
            $ora_oggi = date('G');
            $to_delete_index = -1;
            if ($ora_prenotazione_attiva != '' && $ora_oggi >= intval($ora_prenotazione_attiva)) {
                if (count($prenotazioni) > 0) {

                    foreach ($prenotazioni as $key => $prenotazione) {
                        if (date('d/m/Y', strtotime(\fab\functions::date_to_sql($prenotazione['data_inizio']))) == $data_oggi) {
                            $to_delete_index = $key;
                        }
                    }
                    if ($to_delete_index >= 0) {
                        unset($prenotazioni[$to_delete_index]);
                    }
                }
            }
            return $prenotazioni;
        }

        private function _send_email_new_prenotazione($id_prenotazione, $allegato)
        {
            return $this->_send_email_prenotazione($id_prenotazione, 'new', $allegato);
        }

        private function _send_email_del_prenotazione($id_prenotazione)
        {
            return $this->_send_email_prenotazione($id_prenotazione, 'del');
        }

        private function _send_email_prenotazione($id_prenotazione, $tipo, $allegato = array())
        {
            $this->data['prenotazione'] = $this->prenotazione->get_row_by_id($id_prenotazione);
            if ($this->data['prenotazione']) {
                $user_info = get_userdata($this->data['prenotazione']['id_user']);
                $this->data['prenotazione']['user'] = $user_info;

                $headers = array('Content-Type: text/html; charset=UTF-8');
                $subject = $this->_replace_special(get_option('fabcalpre-email-subject-' . $tipo), $this->data['prenotazione']);
                $message = $this->_replace_special(wpautop(get_option('fabcalpre-email-message-' . $tipo)), $this->data['prenotazione']);

                return wp_mail($user_info->user_email, $subject, $message, $headers, $allegato);
            }
            return false;
        }

        private function _replace_special($text, $prenotazione, $sostituisci_vuote_con = '')
        {
            $special_values = array(
                'prenotazione_id' => $prenotazione['id'],
                'risorsa_nome' => $prenotazione['risorsa']['nome'],
                'data_inizio' => date('d/m/Y', strtotime(\fab\functions::date_to_sql($prenotazione['data_inizio']))),
                'user_first_name' => $prenotazione['user']->first_name,
                'user_last_name' =>  $prenotazione['user']->last_name,
                'user_birth_date' => $prenotazione['user']->birth_date,
                'user_luogo_nascita' => $prenotazione['user']->luogo_nascita,
                'user_country' => $prenotazione['user']->country,
                'user_gender' => $prenotazione['user']->gender[0],
                'user_comune' => $prenotazione['user']->comune,
                'user_provincia' => $prenotazione['user']->provincia,
                'user_indirizzo' => $prenotazione['user']->indirizzo,
                'user_documento_tipo' => (isset($prenotazione['user']->documento_tipo[0]) ? $prenotazione['user']->documento_tipo[0] : ''),
                'user_documento_numero' => $prenotazione['user']->documento_numero,
                //'user_email' => array('user', 'user_email'),

            );
            foreach ($special_values as $key => $value) {
                if (\is_string($value)) {
                    if ($value == '') {
                        $value = $sostituisci_vuote_con;
                    }
                    $text = str_replace('{' . $key . '}', $value, $text);
                } else {
                    echo 'ERRORE:' . $key . '' . print_r($value);
                }
            }
            return $text;
        }

        public function default_forms_fields()
        {
            $html = '';
            $html .= \fab\functions::html_select_search('Risorsa', 'id_risorsa', \fab\functions::options_array($this->params['id_risorsa'], $this->data['risorsa'], true));
            $html .= \fab\functions::html_input_search('Data', 'data_prenotazione', $this->params['data_prenotazione'], 'it-date-datepicker');
            $html .= \fab\functions::html_select_search('Cestino', 'deleted', \fab\functions::options_deleted($this->params['deleted']));
            $html .= \fab\functions::html_input_search('Per pag.', 'paging', $this->params['paging'], '', 'size="3"');
            return $html;
        }

        private function _generate_pdf($id_prenotazione)
        {

            $this->data['prenotazione'] = $this->prenotazione->get_row_by_id($id_prenotazione);
            if ($this->data['prenotazione']) {
                $user_info = get_userdata($this->data['prenotazione']['id_user']);
                $this->data['prenotazione']['user'] = $user_info;
                $subject = $this->_replace_special(get_option('fabcalpre-pdf-title'), $this->data['prenotazione']);
                $html = $this->_replace_special(wpautop(get_option('fabcalpre-pdf-html')), $this->data['prenotazione'], '__________________');

                //require_once(FAB_BASE_PLUGIN_DIR_PATH."vendor/TCPDF/examples/tcpdf_include.php");
                require FAB_BASE_PLUGIN_DIR_PATH . "vendor/autoload.php";
                $pdf = new \TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor(get_bloginfo('name'));
                $pdf->SetTitle($subject);

                // remove default header/footer
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);

                // set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

                // set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

                // set auto page breaks
                $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

                // set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

                // set font
                $pdf->SetFont('helvetica', '', 10);

                // add a page
                $pdf->AddPage();

                // print a block of text using Write()
                $pdf->writeHTML($html, true, false, false, false, '');

                // ---------------------------------------------------------

                //Close and output PDF document
                $upload_dir = wp_upload_dir();
                $base_upload_dir = $upload_dir['basedir'];
                $dir = $base_upload_dir . "/files/fabcalpre/" . $this->data['prenotazione']['id'];
                if (wp_mkdir_p($dir) === true) {
                    // cartella creata con successo
                }
                //echo $dest_upload_dir;
                $pdf_path = join(DIRECTORY_SEPARATOR, array($dir, 'pdf-prenotazione-' . $this->data['prenotazione']['id'] . '.pdf'));
                $pdf->Output($pdf_path, 'F');
                //$pdf->Output($dir . '/pdf-prenotazione-' . $this->data['prenotazione']['id'] . '.pdf', 'I');

                return $pdf_path;
            }
            return false;
        }

        public function ajax_csv()
        {
            if (isset($_GET['csv_action'])) {
                if (method_exists($this, $_GET['csv_action'])) {
                    $this->{$_GET['csv_action']}();

                    parent::ajax_csv();
                    exit();
                } else {
                    echo "Non esiste: " . $_GET['csv_action'];
                    exit();
                }
            } else {
                $this->prenotazione->paging = 0;
                $this->home();
            }

            header("Content-type: application/x-msdownload", true, 200);
            header("Content-Disposition: attachment; filename=prenotazioni.csv");
            header("Pragma: no-cache");
            header("Expires: 0");

            $this->data['cols'] = array(
                'id' => '',
                'risorsa' => '',
                'email' => '',
                'nome' => '',
                'cognome' => '',
                'data' => '',
                'confermata' => '',
                'annullata' => '',
            );

            $newline = PHP_EOL;
            $csv = "";
            if (count($this->data['rows']) > 0) {
                $sep = "";
                foreach ($this->data['cols'] as $col => $default_value) {
                    $csv .= $sep . \fab\functions::clean_col_name($col);
                    $sep = ";";
                }
                $csv .= $newline;
                foreach ($this->data['rows'] as $row) {
                    $user_info = get_userdata($row['id_user']);

                    $sep = "";
                    $csv .= $sep . $row['id'];
                    $sep = ";";
                    $csv .= $sep . '"' . $row['risorsa']['nome'] . '"';
                    $csv .= $sep .  '"' . $row['user']['user_email'] . '"';
                    $csv .= $sep . '"' .  $user_info->first_name  . '"';
                    $csv .= $sep . '"' .  $user_info->last_name . '"';
                    $csv .= $sep . '"' . $row['data_inizio'] . '"';
                    $csv .= $sep . '"' . ($row['confirmed'] == 0 ? 'NO' : 'SI') . '"';
                    $csv .= $sep . '"' . ($row['deleted'] == 0 ? 'NO' : 'SI') . '"';

                    $csv .= $newline;
                }
            }
            echo $csv;
            exit();
        }
    }
}
