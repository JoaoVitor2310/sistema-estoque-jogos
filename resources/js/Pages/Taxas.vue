<script setup lang="ts">
// import type { ApiErrorResponse } from '@/types/ApiErrorResponse';
// import type { ApiOkResponse } from '@/types/ApiOkResponse';
import { createApp, reactive, ref } from 'vue';
import axiosInstance from '../axios';
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

// onMouted
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
const DialogVisible = ref(false); // Visibilidade do Dialog(modal)

const selected = reactive({
  id: 0,
  nome: '',
  preco: 0,
})
const onEdit = (product: any) => {
  Object.assign(selected, product);
  DialogVisible.value = true;
}

const handleSaveButton = async (): Promise<void> => {
  const res = await axiosInstance.put(`/fees/${selected.id}`, { preco: selected.preco });
  console.log(res.data);
  if (res.status === 200 || res.status === 201) {
    toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Taxa atualizada com sucesso', life: 3000 });
    const itemToUpdate = rowData.find(item => item.id === selected.id);
    if (itemToUpdate) {
      console.log("itemToUpdate")
      console.log(itemToUpdate)
      Object.assign(itemToUpdate, res.data.data);
      // itemToUpdate.preco = selected.preco;
      console.log(itemToUpdate)
    }
  } else {
    const errorMessages = Object.values(res.data.errors)
      .flat() // Se for um array de mensagens, o flat junta todas as mensagens
      .join(', '); // Concatena as mensagens com uma vírgula ou outro separador

    toast.add({
      severity: 'error',
      summary: 'Erro',
      detail: errorMessages,
      life: 3000
    });
  }
  DialogVisible.value = false;
}

const handleDeleteButton = async (product: any): Promise<void> => {
  Object.assign(selected, product);
  console.log(selected);
}

</script>

<template>
  <Toast position="bottom-right" />
  <Dialog v-model:visible="DialogVisible" modal header="Criar/Editar" :style="{ width: '50rem' }">
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
      <Button type="button" label="Cancelar" severity="secondary" @click="DialogVisible = false"></Button>
      <Button type="button" label="Salvar" @click="handleSaveButton"></Button>
    </div>
  </Dialog>

  <div class="container text-center">

    <h1>Taxas de Marketplaces</h1>
    <!-- {{ selectedProduct }} -->

    <DataTable :value="props.taxas" stripedRows sortMode="multiple" removableSort
      :globalFilterFields="['nome', 'preco']" v-model:filters="filters" v-model:selection="selectedProduct"
      selectionMode="single" scrollable scrollHeight="100vh" editMode="cell" dataKey="id" size="large" tableStyle="min-width: 50rem">
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
          <div class="d-flex gap-1">
            <Button label="Editar" aria-label="Editar" icon="pi pi-pencil" @click="onEdit(slotProps.data)" raised />
            <Button label="Excluir" aria-label="Excluir" icon="pi pi-times" @click="handleDeleteButton(slotProps.data)" raised />
          </div>
        </template>
      </Column>
    </DataTable>
  </div>
</template>