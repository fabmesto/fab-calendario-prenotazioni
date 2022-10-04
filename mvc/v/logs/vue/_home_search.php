<v-card>
    <v-card-title>
        <v-text-field v-model="search.id" label="ID" class="mx-4"></v-text-field>
        <v-text-field v-model="search.category" label="Category" class="mx-4"></v-text-field>
        <v-text-field v-model="search.subject" label="Subject" class="mx-4"></v-text-field>
        <v-select v-model="search.deleted" :items="[{t:'No',v:0},{t:'Si',v:1}]" item-text="t" item-value="v" clearable label="Cestino" class="mx-4"></v-select>
    </v-card-title>
</v-card>