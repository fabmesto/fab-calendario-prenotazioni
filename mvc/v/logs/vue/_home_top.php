<template v-slot:top>
    <v-toolbar flat>
        <v-toolbar-title><?php echo ucwords($this->name) ?></v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="700px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-overlay :value="save_loading">
                            <div class="text-center">
                                <v-progress-circular indeterminate color="primary"></v-progress-circular>
                            </div>
                        </v-overlay>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field v-model="editedItem.subject" label="Subject"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model="editedItem.message" label="message"></v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model="editedItem.json_data" label="json_data"></v-textarea>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="editedItem.ip" label="ip"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">
                        Annulla
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="save">
                        Salva
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Sei sicuro di eliminare questa riga?</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete">Annulla</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm">Elimina</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
            <v-overlay :value="delete_loading">
                <div class="text-center">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
            </v-overlay>
        </v-dialog>
    </v-toolbar>
</template>