<?php echo $this->require_to_html(FAB_BASE_PLUGIN_DIR_PATH . 'fab_base/base_content_header3.php') ?>
<div id="vue-datatable" basereadurl="<?php echo $this->url_rest_read ?>" basesaveurl="<?php echo $this->url_rest_save ?>" basedeleteurl="<?php echo $this->url_rest_save ?>" modelname="<?php echo $this->default_model_name ?>">
    <v-app>
        <v-main>
            <?php echo $this->require_to_html($this->parent->PLUGIN_DIR_PATH . 'mvc/v/' . $this->name . '/vue/_home_search.php') ?>
            <v-data-table :headers="headers" :items="rows" :options.sync="options" :server-items-length="totalRows" :loading="loading" :expanded.sync="expanded" item-key="id" show-expand class="elevation-1" :footer-props="{itemsPerPageOptions:[10,20,50]}">
                <?php echo $this->require_to_html($this->parent->PLUGIN_DIR_PATH . 'mvc/v/' . $this->name . '/vue/_home_top.php') ?>

                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length">
                        <div v-for="header in headersOthers">
                            <b>{{header.text}}</b>: {{item[header.value]}}
                        </div>
                    </td>
                </template>
                <?php if ($this->can_delete()) : ?>
                    <template v-slot:item.actions="{ item }">
                        <v-icon small color="blue" class="mr-2" @click="editItem(item)" v-if="basesaveurl">
                            mdi-pencil
                        </v-icon>
                        <v-icon small color="red" @click="deleteItem(item)" v-if="basedeleteurl">
                            mdi-delete
                        </v-icon>
                    </template>
                <?php endif; ?>
            </v-data-table>


        </v-main>
    </v-app>

</div>

<?php echo $this->require_to_html(FAB_BASE_PLUGIN_DIR_PATH . 'fab_base/base_content_footer3.php') ?>