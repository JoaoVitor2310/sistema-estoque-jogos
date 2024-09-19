<script setup lang="ts">
// import type { ApiErrorResponse } from '@/types/ApiErrorResponse';
// import type { ApiOkResponse } from '@/types/ApiOkResponse';
import { createApp, reactive, ref } from 'vue';
// const apiUrl = import.meta.env.VITE_API_URL;

// PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from '@primevue/core/api';
// import IconField from 'primevue/iconfield';
// import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import 'primeicons/primeicons.css'
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';

// Inertia
import { router } from '@inertiajs/vue3'
import axios from 'axios';

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

import { useToast } from "primevue/usetoast";
const toast = useToast();

const selectedProduct = ref();
const visible = ref(false);

const selected = reactive({
  id: 0,
  nome: '',
  preco: 0,
})
const onEdit = (product: any) => {
  console.log(product);
  Object.assign(selected, product);
  visible.value = true;
}

const handleDialog = async () => {
  try {
    const res = await axios.put(`/fees/${selected.id}`, { preco: selected.preco }, {
      validateStatus: function (status) {
        // Retorna true para que qualquer status seja considerado válido
        return status >= 200 && status < 500; // Considere 2xx e 4xx como válidos (não lança exceção)
      }
    });

    if (res.status === 200 || res.status === 201) {
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Taxa atualizada com sucesso', life: 3000 });
    } else {
      toast.add({ severity: 'error', summary: 'Erro', detail: 'Ocorreu um erro ao atualizar a taxa', life: 3000 });
    }
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      const errorMessages = Object.values(error.response.data.errors)
        .flat() // Se for um array de mensagens, o flat junta todas as mensagens
        .join(', '); // Concatena as mensagens com uma vírgula ou outro separador

      // Exibe todas as mensagens de erro concatenadas
      toast.add({
        severity: 'error',
        summary: 'Erro',
        detail: errorMessages,
        life: 3000
      });
    } else {
      // Se não houver mensagens de erro específicas, exibe uma mensagem genérica
      toast.add({
        severity: 'error',
        summary: 'Erro',
        detail: 'Ocorreu um erro ao atualizar a taxa',
        life: 3000
      });
    }
  }


  visible.value = false;
}

</script>

<template>
  <Toast position="bottom-right" />
  <Dialog v-model:visible="visible" modal header="Criar/Editar" :style="{ width: '50rem' }">
    <span class="text-surface-500 dark:text-surface-400 d-block mb-3">Crie ou edite os dados.</span>
    <div class="d-flex items-center gap-4 mb-2">
      <label for="nome" class="font-semibold w-24">Nome</label>
      <InputText id="nome" class="flex-auto" disabled v-model="selected.nome" />
    </div>
    <div class="d-flex items-center gap-4 mb-8">
      <label for="preco" class="font-semibold w-24">Preço</label>
      <InputText id="preco" class="flex-auto" v-model="selected.preco" />
    </div>
    <div class="d-flex justify-content-end gap-2">
      <Button type="button" label="Cancelar" severity="secondary" @click="visible = false"></Button>
      <Button type="button" label="Salvar" @click="handleDialog"></Button>
    </div>
  </Dialog>

  <div class="container text-center">

    <h1>Taxas de Marketplaces</h1>
    <!-- {{ selectedProduct }} -->

    <DataTable :value="props.taxas" stripedRows sortMode="multiple" removableSort
      :globalFilterFields="['nome', 'preco']" v-model:filters="filters" v-model:selection="selectedProduct"
      selectionMode="single" dataKey="id" size="large" tableStyle="min-width: 50rem">
      <template #header>
        <div class="d-flex justify-content-between">
          <div>
            <Button label="Novo" aria-label="Novo" icon="pi pi-plus" raised>

            </Button>
          </div>
          <div class="w-25">
            <InputGroup>
              <InputGroupAddon>
                <i class="pi pi-search" />
              </InputGroupAddon>
              <InputText v-model="filters['global'].value" placeholder="Pesquisar" />
            </InputGroup>
          </div>
        </div>
      </template>
      <template #empty> Nenhum item encontrado. </template>
      <Column field="id" header="ID" sortable></Column>
      <Column field="nome" header="Nome" sortable></Column>
      <Column field="preco" header="Preço" sortable></Column>
      <Column header="Ação">
        <template #body="slotProps">
          <Button label="Editar" aria-label="Editar" icon="pi pi-pencil" @click="onEdit(slotProps.data)" raised />
        </template>
      </Column>
    </DataTable>
  </div>
</template>