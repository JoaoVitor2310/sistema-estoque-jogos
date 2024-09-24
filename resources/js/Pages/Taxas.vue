<script setup lang="ts">
import { reactive, ref } from 'vue';
import axiosInstance from '../axios';

// PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from '@primevue/core/api';
import InputText from 'primevue/inputtext';
import 'primeicons/primeicons.css'
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import ConfirmPopup from 'primevue/confirmpopup';
import { useConfirm } from "primevue/useconfirm";
import InputNumber from 'primevue/inputnumber';

// Inertia
import { showResponse } from '../helpers/showResponse';

// onMouted {
let rowData: any[] = reactive([]);
const props = defineProps({ taxas: Array });
console.log(props.taxas)
Object.assign(rowData, props.taxas);
// }

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  nome: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  preco: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  action: { value: null, matchMode: FilterMatchMode.IN },
});

const toast = useToast();
const confirm = useConfirm();

const selectedProduct = ref();
const DialogVisible = ref(false); // Visibilidade do Dialog(modal)
const isEdit = ref(false); // Variável que define se é para criar ou editar no Dialog

const selected = reactive({
  id: 0,
  nome: '',
  preco: 0,
})

const onEdit = async (product: any) => {
  isEdit.value = true;
  const res = await axiosInstance.put(`/fees/${product.id}`, { preco: product.preco });
  showResponse(res, toast.add);

  if (res.status === 200) {
    const itemToUpdate = rowData.find(item => item.id === product.id);
    if (itemToUpdate) {
      Object.assign(itemToUpdate, res.data.data);
    }
  }
  DialogVisible.value = false;
}

const handleAddButton = async (): Promise<void> => { // Mostra o dialog com o elemento clicado
  isEdit.value = false;
  Object.assign(selected, { // Zera o valor de selected para criar um novo
    id: 0,
    nome: '',
    preco: null
  });
  DialogVisible.value = true;
}

const onAdd = async (newFee: any): Promise<void> => { // Faz a req pra api add o elemento
  const res = await axiosInstance.post(`/fees`, newFee);
  showResponse(res, toast.add);
  DialogVisible.value = false;
  console.log(res.data.data)
  rowData.push(res.data.data);
  console.log(rowData)
}

const handleDeleteButton = (event: any) => {
  confirm.require({
    target: event.currentTarget,
    message: 'Tem certeza que deseja excluir esta taxa?',
    // icon: 'pi pi-info-circle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true
    },
    acceptProps: {
      label: 'Excluir',
      severity: 'danger'
    },
    accept: async () => {
      const res = await axiosInstance.delete(`/fees/${selected.id}`);
      showResponse(res, toast.add);
      const itemToDelete = rowData.findIndex(item => item.id === selected.id);
      console.log(itemToDelete);
      rowData.splice(itemToDelete, 1);
      DialogVisible.value = false;
    }
  });
};

</script>

<template>
  {{ selected }}
  <Toast position="bottom-right" />
  <ConfirmPopup />
  <Dialog v-model:visible="DialogVisible" modal :header="isEdit ? 'Editar' : 'Criar'" :style="{ width: '50rem' }">
    <span class="text-surface-500 dark:text-surface-400 d-block mb-3">Crie ou edite os dados.</span>
    <div class="d-flex items-center gap-4 mb-2">
      <label for="nome" class="font-semibold w-24">Nome</label>
      <InputText id="nome" class="flex-auto" :disabled="isEdit ? true : false" v-model="selected.nome" />
    </div>
    <div class="d-flex items-center gap-4 mb-8">
      <label for="preco" class="font-semibold w-24">Preço</label>
      <InputNumber id="preco" class="flex-auto" v-model="selected.preco" mode="decimal" :minFractionDigits="3"
        :maxFractionDigits="3" useGrouping />
    </div>
    <div class="d-flex justify-content-end gap-2">
      <Button type="button" label="Cancelar" severity="secondary" @click="DialogVisible = false"></Button>
      <Button type="button" label="Salvar" @click="isEdit ? onEdit(selected) : onAdd(selected)"></Button>
    </div>
  </Dialog>

  <div class="container text-center">

    <h1>Taxas de Marketplaces</h1>
    <!-- {{ selectedProduct }} -->

    <DataTable :value="rowData" stripedRows sortMode="multiple" removableSort
      :globalFilterFields="['nome', 'preco']" v-model:filters="filters" v-model:selection="selectedProduct"
      selectionMode="single" scrollable scrollHeight="100vh" editMode="cell" dataKey="id" size="large"
      tableStyle="min-width: 50rem">
      <template #header>
        <div class="d-flex justify-content-between">
          <div>
            <Button label="Novo" aria-label="Novo" icon="pi pi-plus" @click="handleAddButton()" raised />
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
      <Column field="preco" header="Preço" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="3"
            :maxFractionDigits="3" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column header="Ação">
        <template #body="slotProps">
          <div class="d-flex gap-1">
            <Button label="Editar" aria-label="Editar" icon="pi pi-pencil"
              @click="DialogVisible = true; Object.assign(selected, slotProps.data); isEdit = true" raised />
            <Button label="Excluir" aria-label="Excluir" icon="pi pi-times"
              @click="handleDeleteButton($event); Object.assign(selected, slotProps.data);" raised />
          </div>
        </template>
      </Column>
    </DataTable>
  </div>
</template>