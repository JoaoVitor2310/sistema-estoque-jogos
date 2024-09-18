<script setup lang="ts">
import AgGrid from '@/components/AgGrid.vue';
import type { ApiErrorResponse } from '@/types/ApiErrorResponse';
import type { ApiOkResponse } from '@/types/ApiOkResponse';
import { createApp, reactive, ref } from 'vue';
const apiUrl = import.meta.env.VITE_API_URL;
import EditButton from '@/components/EditButton.vue';
// PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';                   // optional
import { FilterMatchMode } from '@primevue/core/api';
// import IconField from 'primevue/iconfield';
// import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import 'primeicons/primeicons.css'
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';

// Definindo os dados da tabela
let rowData: any[] = reactive([]);

const props = defineProps({ taxas: Array });
console.log(props.taxas)
Object.assign(rowData, props.taxas);

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nome: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  preco: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  action: { value: null, matchMode: FilterMatchMode.IN },
});

const selectedProduct = ref();

</script>

<template>
  <div class="container text-center">

    <h1>Taxas de Marketplaces</h1>

    <!-- <AgGrid :col-defs="colDefs" :row-data="rowData"></AgGrid> -->
     {{ selectedProduct }}
    
    <DataTable :value="props.taxas" stripedRows sortMode="multiple" removableSort
    :globalFilterFields="['nome', 'preco']" v-model:filters="filters" v-model:selection="selectedProduct" selectionMode="single" dataKey="id" tableStyle="min-width: 50rem">
    <template class="flex justify-end" #header>
        <div class="w-25">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-search" />
            </InputGroupAddon>
            <InputText v-model="filters['global'].value" placeholder="Pesquisar" />
          </InputGroup>
        </div>
      </template>
      <template #empty> Nenhum item encontrado. </template>
      <Column field="id" header="ID" sortable></Column>
      <Column field="nome" header="Nome" sortable></Column>
      <Column field="preco" header="Preço" sortable></Column>
      <Column header="Ação" sortable>
        Editar
      </Column>
    </DataTable>
  </div>
</template>
