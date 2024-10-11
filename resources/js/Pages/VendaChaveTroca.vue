<script setup lang="ts">
import { reactive, ref } from 'vue';
import type { PropType } from 'vue';
import axiosInstance from '../axios';
import { GameLine } from '../types/GameLine';
import dayjs from 'dayjs';

// Inertia
import { showResponse } from '../helpers/showResponse';

// PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode } from '@primevue/core/api';
import InputText from 'primevue/inputtext';
import 'primeicons/primeicons.css'
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";
import ConfirmPopup from 'primevue/confirmpopup';
import { useConfirm } from "primevue/useconfirm";
import InputNumber from 'primevue/inputnumber';
import RadioButton from 'primevue/radiobutton';
import Select from 'primevue/select';
import DatePicker from 'primevue/datepicker';
import Paginator, { PageState } from 'primevue/paginator';
import MultiSelect from 'primevue/multiselect';
import { formatDateToBR, formatDateToDB } from '../helpers/formatHelpers';

// onMouted {
let rowData: GameLine[] = reactive([]);
const props = defineProps({ games: Array, totalGames: Number, pagination: Object, tiposFormato: Array, tiposLeilao: Array, plataformas: Array, tiposReclamacao: Array });
console.log(props.tiposFormato);
Object.assign(rowData, props.games);
// }

const filters = ref({
  searchField: { value: null, matchMode: FilterMatchMode.IN },
});

const toast = useToast();
const confirm = useConfirm();

const selectedProduct = ref();
const DialogVisible = ref(false); // Visibilidade do Dialog(modal)
const isEdit = ref(false); // Variável que define se é para criar ou editar no Dialog
const localTotalGames = ref(props.totalGames);

const selected = reactive({
  id: 0,
  tipo_reclamacao_id: 1,
  steamId: '',
  tipo_formato_id: 1,
  chaveRecebida: '',
  nomeJogo: '',
  precoJogo: null,
  notaMetacritic: null,
  isSteam: false,
  observacao: '',
  id_leilao_G2A: 1,
  id_leilao_gamivo: 1,
  id_leilao_kinguin: 1,
  id_plataforma: 1,
  precoCliente: null,
  chaveEntregue: '',
  valorPagoTotal: '',
  valorPagoIndividual: null,
  vendido: false,
  leiloes: null,
  quantidade: null,
  devolucoes: false,
  dataAdquirida: '',
  dataVenda: '',
  dataVendida: '',
  perfilOrigem: '',
  email: '',
  qtdTF2: null, // A partir daqui é opcional para criar
  somatorioIncomes: null,
  primeiroIncome: null,
})

const handleEditButton = (data: any) => {
  console.log(data);
  DialogVisible.value = true;
  isEdit.value = true;

  Object.assign(selected, data);

  selected.tipo_formato_id = data.tipo_formato.id;
};


const onEdit = async (product: Partial<GameLine>) => {
  product['tipo_reclamacao_id'] = product.tipo_reclamacao?.id;
  product['tipo_formato_id'] = product.tipo_formato?.id;
  product['id_plataforma'] = product.plataforma?.id;
  console.log(product);
  isEdit.value = true;
  try {
    const res = await axiosInstance.put(`/venda-chave-troca/${product.id}`, product);
    showResponse(res, toast.add);

    if (res.status === 200) {
      const itemToUpdate = rowData.find(item => item.id === product.id);
      if (itemToUpdate) {
        Object.assign(itemToUpdate, res.data.data);
      }
      DialogVisible.value = false;
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro Interno, tente novamente.',
      detail: error,
      life: 3000
    });
    console.log(error);
  }
}

const handleAddButton = async (): Promise<void> => { // Mostra o dialog com o elemento clicado
  isEdit.value = false;
  Object.assign(selected, { // Zera o valor de selected para criar um novo
    id: 0,
    tipo_reclamacao_id: 1,
    steamId: '',
    tipo_formato_id: 1,
    chaveRecebida: '',
    nomeJogo: '',
    precoJogo: null,
    notaMetacritic: null,
    isSteam: false,
    observacao: '',
    id_leilao_G2A: 1,
    id_leilao_gamivo: 1,
    id_leilao_kinguin: 1,
    id_plataforma: 1,
    precoCliente: null,
    chaveEntregue: '',
    valorPagoTotal: '',
    valorPagoIndividual: null,
    vendido: false,
    leiloes: null,
    quantidade: null,
    devolucoes: false,
    dataAdquirida: '',
    dataVenda: '',
    dataVendida: '',
    perfilOrigem: '',
    email: '',
    qtdTF2: null, // A partir daqui é opcional para criar
    somatorioIncomes: null,
    primeiroIncome: null,
  });
  DialogVisible.value = true;
}

const onAdd = async (newResource: Partial<GameLine>): Promise<void> => { // Faz a req pra api add o elemento
  try {
    const res = await axiosInstance.post(`/venda-chave-troca`, newResource);
    showResponse(res, toast.add);
    if (res.status === 200 || res.status === 201) {
      DialogVisible.value = false;
      console.log(res.data.data);
    }
    rowData.unshift(res.data.data); // Adiciona no início do array
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro Interno, tente novamente.',
      detail: error,
      life: 3000
    });
    console.log(error);
  }
}

const handleDeleteButton = (event: any, qtd: number) => {
  confirm.require({
    target: event.currentTarget,
    message: qtd === 1 ? 'Tem certeza que deseja excluir este item?' : 'Tem certeza que deseja excluir esses itens?',
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
      if (qtd === 1) {
        const res = await axiosInstance.delete(`/venda-chave-troca/${selected.id}`);
        showResponse(res, toast.add);
        const itemToDelete = rowData.findIndex(item => item.id === selected.id);
        console.log(itemToDelete);
        rowData.splice(itemToDelete, 1);
        DialogVisible.value = false;
      } else {
        const res = await axiosInstance.delete(`/venda-chave-troca`, {
          params: {
            games: selectedProduct.value
          }
        });
        showResponse(res, toast.add);
        const selectedProductIds = selectedProduct.value.map(item => item.id);
        const filteredRowData = rowData.filter(item => !selectedProductIds.includes(item.id));
        rowData.splice(0, rowData.length, ...filteredRowData);
        selectedProduct.value = null;
      }
    }
  });
};

const pagination = ref(props.pagination!); // Informações da paginação
const currentFirst = ref((pagination.value.current_page - 1) * pagination.value.per_page);
const isSearching = ref(false);

const searchFilter = reactive({
  tipo_reclamacao_id: [],
  steamId: '',
  tipo_formato_id: [],
  chaveRecebida: '',
  nomeJogo: '',
  isSteam: [],
  randomClassificationG2A: '',
  randomClassificationKinguin: '',
  id_plataforma: [],
  chaveEntregue: '',
  valorPagoTotal: '',
  vendido: [],
  devolucoes: [],
  dataAdquirida: '',
  dataVenda: '',
  dataVendida: '',
  perfilOrigem: '',
  email: '',
})

const handlePageChange = (event: PageState) => { // Teve que ser criada por que o evento não pode ser passado com outro argumento junto
  onPageChange(false, event);
};

// Função chamada ao mudar de página
const onPageChange = async (search: boolean, event: PageState | null = null) => {
  if (search) isSearching.value = true;
  const limit = event ? event.rows : 100;
  const page = event ? event.page + 1 : 1; // Paginator começa em 0. 1 como página padrão

  let url = `/venda-chave-troca/paginated?limit=${limit}&page=${page}`;
  let method = 'GET';

  if (isSearching.value) {
    searchFilter.dataAdquirida = formatDateToDB(searchFilter.dataAdquirida);
    searchFilter.dataVenda = formatDateToDB(searchFilter.dataVenda);
    searchFilter.dataVendida = formatDateToDB(searchFilter.dataVendida);
    url = `/venda-chave-troca/search?page=${page}`;
    method = 'POST';
  }

  try {
    const res = await axiosInstance(url, {
      method,
      data: method === 'POST' ? searchFilter : null
    });

    if (res.status === 200 || res.status === 201) {
      localTotalGames.value = res.data.data.totalGames;
      rowData.splice(0, rowData.length, ...res.data.data.games.data);
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Erro Interno, tente novamente.',
      detail: error,
      life: 3000
    });
    console.log(error);
  }
};

</script>

<template>
  <Toast position="bottom-right" />
  <ConfirmPopup />
  <Dialog v-model:visible="DialogVisible" modal :header="isEdit ? 'Editar' : 'Criar'" :style="{ width: '50rem' }">
    <span class="d-block mb-3" v-if="!isEdit">Insira os dados para criar.</span>
    <span class="d-block mb-3" v-if="isEdit">Edite os dados.</span>
    <div class="d-flex flex-column">
      <label class="fw-bold">Tipo de Reclamação</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.tipo_reclamacao_id" :options="props.tiposReclamacao" optionLabel="name"
          optionValue="id" class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">SteamID</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.steamId" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Formato</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.tipo_formato_id" :options="props.tiposFormato" optionValue="id" optionLabel="name"
          placeholder="Formato do Jogo" class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Chave Recebida</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.chaveRecebida" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Nome do jogo</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.nomeJogo" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Preço do Jogo</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.precoJogo" mode="decimal" showButtons :minFractionDigits="2"
          :maxFractionDigits="2" useGrouping />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Nota Metacritic</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model.number="selected.notaMetacritic" showButtons :min="0" :max="100" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">É Steam?</label>
      <div class="d-flex gap-5 mb-3">
        <label for="ingredient1" class="">Sim</label>
        <RadioButton v-model="selected.isSteam" :value="true" />
        <label for="ingredient1" class="">Não</label>
        <RadioButton v-model="selected.isSteam" :value="false" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Observação</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.observacao" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Leilão G2A</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.id_leilao_G2A" :options="props.tiposLeilao" optionLabel="name" optionValue="id"
          class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Leilão Gamivo</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.id_leilao_gamivo" :options="props.tiposLeilao" optionLabel="name" optionValue="id"
          class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Leilão Kinguin</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.id_leilao_kinguin" :options="props.tiposLeilao" optionLabel="name" optionValue="id"
          class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Plataforma</label>
      <div class="d-flex gap-5 mb-3">
        <Select v-model="selected.id_plataforma" :options="props.plataformas" optionLabel="name" optionValue="id"
          class="w-full md:w-56" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Preço Cliente</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.precoCliente" mode="decimal" showButtons :minFractionDigits="2"
          :maxFractionDigits="2" useGrouping />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Quantidade de TF2</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.qtdTF2" mode="decimal" showButtons :minFractionDigits="2"
          :maxFractionDigits="2" useGrouping />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Somatório dos Incomes</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.somatorioIncomes" showButtons mode="decimal"
          :minFractionDigits="2" :maxFractionDigits="2" useGrouping />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Primeiro Income</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.primeiroIncome" showButtons mode="decimal"
          :minFractionDigits="2" :maxFractionDigits="2" useGrouping />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Chave Entregue</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.chaveEntregue" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Valor Pago Total</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.valorPagoTotal" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Vendido</label>
      <div class="d-flex gap-5 mb-3">
        <!-- <InputText  class="flex-auto" v-model="selected.name" /> -->
        <label for="ingredient1">Sim</label>
        <RadioButton v-model="selected.vendido" :value="true" />
        <label for="ingredient1">Não</label>
        <RadioButton v-model="selected.vendido" :value="false" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Leilões</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.leiloes" showButtons :min="0" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Quantidade</label>
      <div class="d-flex gap-5 mb-3">
        <InputNumber class="flex-auto" v-model="selected.quantidade" showButtons :min="0" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Devoluções</label>
      <div class="d-flex gap-5 mb-3">
        <label>Sim</label>
        <RadioButton v-model="selected.devolucoes" :value="true" />
        <label>Não</label>
        <RadioButton v-model="selected.devolucoes" :value="false" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Data Adquirida</label>

      <div class="d-flex gap-5 mb-3">
        <DatePicker v-model="selected.dataAdquirida" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
          showButtonBar />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Data Venda</label>
      <div class="d-flex gap-5 mb-3">
        <DatePicker v-model="selected.dataVenda" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
          showButtonBar />
        <!-- <InputText class="flex-auto" v-model="selected.dataVenda" /> -->
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Data Vendida</label>
      <div class="d-flex gap-5 mb-3">
        <DatePicker v-model="selected.dataVendida" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
          showButtonBar />
        <!-- <InputText class="flex-auto" v-model="selected.dataVendida" /> -->
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Perfil/Origem</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.perfilOrigem" />
      </div>
    </div>
    <div class="d-flex flex-column">
      <label class="fw-bold">Email</label>
      <div class="d-flex gap-5 mb-3">
        <InputText class="flex-auto" v-model="selected.email" />
      </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <Button type="button" label="Cancelar" severity="secondary" @click="DialogVisible = false"></Button>
      <Button type="button" label="Salvar" @click="isEdit ? onEdit(selected) : onAdd(selected)"></Button>
    </div>
  </Dialog>

  <div class="text-center mb-3 mx-5">
    <h1>Venda-Chave-Troca</h1>
    <div class="w-50 m-auto">
      <p>Lista de jogos(chaves) vendidos, para vender e para trocar.</p>
    </div>
    <DataTable :value="rowData" stripedRows sortMode="multiple" removableSort v-model:filters="filters"
      filterDisplay="menu" v-model:selection="selectedProduct" selectionMode="multiple" scrollable scrollHeight="100vh"
      editMode="cell" dataKey="id" size="small" tableStyle="min-width: 50rem">
      <template #header>
        <div class="d-flex justify-content-between">
          <div class="d-flex gap-2">
            <Button label="Novo" aria-label="Novo" icon="pi pi-plus" @click="handleAddButton()" raised />
            <Button label="Deletar" :disabled="!selectedProduct || selectedProduct.length === 0" aria-label="Deletar"
              severity="danger" icon="pi pi-plus" @click="handleDeleteButton($event, 2)" raised />
          </div>
          <div>
            <Button label="Pesquisar" aria-label="Pesquisar" severity="info" icon="pi pi-search"
              @click="onPageChange(true)" raised />

          </div>
        </div>
      </template>
      <template #empty>
        <h4>
          Nenhum item encontrado.
        </h4>
      </template>
      <Column field="id" header="ID" sortable></Column>
      <Column field="fornecedor.quantidade_reclamacoes" header="Reclamações Anteriores">
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)" size="small"></InputText>
        </template>
      </Column>
      <Column field="tipo_reclamacao.name" header="Reclamação?" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.tipo_reclamacao_id" :options="props.tiposReclamacao" optionLabel="name"
            optionValue="id" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="steamId" header="SteamID" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.steamId" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="tipo_formato.name" header="Formato" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.tipo_formato_id" :options="props.tiposFormato" optionLabel="name"
            optionValue="id" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #editor="{ data, field }">
          <Select v-model="data[field]" :options="props.tiposFormato" @blur="onEdit(data)" optionLabel="name"
            optionValue="id" />
          <!-- <InputText v-model="data[field]" @blur="onEdit(data)"></InputText> -->
        </template>
      </Column>
      <Column field="chaveRecebida" header="Chave Recebida" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.chaveRecebida" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="nomeJogo" header="Nome do Jogo" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.nomeJogo" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="precoJogo" header="Preço do jogo" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="notaMetacritic" header="Nota Metacritic" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="isSteam" header="É Steam?" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.isSteam" :options="[{ name: true }, { name: false }]" optionLabel="name"
            optionValue="name" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #body="{ data }">
          <i class="pi m-1 fw-bold" :class="[
            data.isSteam === true ? 'pi-check-circle' :
                data.isSteam === false ? 'pi-times-circle' : 'pi-question',
            data.isSteam === true ? 'text-primary' :
              data.isSteam === false ? 'text-danger' : ''
          ]">
          </i>
          <!-- {{ data }} -->
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="randomClassificationG2A" header="Classificação G2A" filterField="searchField"
        :showFilterMenu="true" :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.randomClassificationG2A" type="text" placeholder="Pesquisar" />
        </template>
      </Column>
      <Column field="randomClassificationKinguin" header="Classificação Kinguin" filterField="searchField"
        :showFilterMenu="true" :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.randomClassificationKinguin" type="text" placeholder="Pesquisar" />
        </template>
      </Column>
      <Column field="observacao" header="Observação">
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="leilao_g2_a.name" header="Leilão G2A">
        <template #body="{ data }">
          <i class="pi m-1 fw-bold" :class="[
            data.leilao_g2_a.id === 1 ? 'pi-check-circle' :
              data.leilao_g2_a.id === 2 ? 'pi-check-circle' :
                data.leilao_g2_a.id === 3 ? 'pi-times-circle' : 'pi-question',
            data.leilao_g2_a.id === 2 ? 'text-primary' :
              data.leilao_g2_a.id === 3 ? 'text-danger' : ''
          ]">
          </i>
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="leilao_gamivo.name" header="Leilão Gamivo">
        <template #body="{ data }">
          <i class="pi m-1 fw-bold" :class="[
            data.leilao_gamivo.id === 1 ? 'pi-check-circle' :
              data.leilao_gamivo.id === 2 ? 'pi-check-circle' :
                data.leilao_gamivo.id === 3 ? 'pi-times-circle' : 'pi-question',
            data.leilao_gamivo.id === 2 ? 'text-primary' :
              data.leilao_gamivo.id === 3 ? 'text-danger' : ''
          ]">
          </i>
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="leilao_kinguin.name" header="Leilão Kinguin">
        <template #body="{ data }">
          <i class="pi m-1 fw-bold" :class="[
            data.leilao_kinguin.id === 1 ? 'pi-check-circle' :
              data.leilao_kinguin.id === 2 ? 'pi-check-circle' :
                data.leilao_kinguin.id === 3 ? 'pi-times-circle' : 'pi-question',
            data.leilao_kinguin.id === 2 ? 'text-primary' :
              data.leilao_kinguin.id === 3 ? 'text-danger' : ''
          ]">
          </i>
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="plataforma.name" header="Plataforma" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.id_plataforma" :options="props.plataformas" optionLabel="name"
            optionValue="id" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="precoCliente" header="Preço Cliente" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="precoVenda" header="Preço Venda" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="incomeReal" header="Income Real" sortable>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="incomeSimulado" header="Income Simulado" sortable>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="chaveEntregue" header="Chave Entregue" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.chaveEntregue" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="valorPagoTotal" header="Valor Pago Total" filterField="searchField"
        :showFilterMenu="true" :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.valorPagoTotal" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="valorPagoIndividual" header="Valor Pago Individual" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="vendido" header="Vendido" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.vendido" :options="[{ name: true }, { name: false }]" optionLabel="name"
            optionValue="name" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="leiloes" header="Leilões" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="quantidade" header="Quantidade" sortable>
        <template #editor="{ data, field }">
          <InputNumber v-model="data[field]" @blur="onEdit(data)" mode="decimal" :minFractionDigits="2"
            :maxFractionDigits="2" useGrouping autofocus fluid />
        </template>
      </Column>
      <Column field="devolucoes" header="Devoluções" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <MultiSelect v-model="searchFilter.devolucoes" :options="[{ name: true }, { name: false }]" optionLabel="name"
            optionValue="name" style="min-width: 14rem">
          </MultiSelect>
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="lucroRS" header="Lucro(€)" sortable>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="lucroPercentual" header="Lucro(%)" sortable>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="dataAdquirida" header="Data Adquirida" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <DatePicker v-model="searchFilter.dataAdquirida" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
            showButtonBar />
        </template>
        <template #body="slotProps">
          {{ formatDateToBR(slotProps.data.dataAdquirida) }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="dataVenda" header="Data Venda" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <DatePicker v-model="searchFilter.dataVenda" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
            showButtonBar />
        </template>
        <template #body="slotProps">
          {{ formatDateToBR(slotProps.data.dataVenda) }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="dataVendida" header="Data Vendida" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <DatePicker v-model="searchFilter.dataVendida" dateFormat="dd/mm/yy" showIcon fluid :showOnFocus="false"
            showButtonBar />
        </template>
        <template #body="slotProps">
          {{ formatDateToBR(slotProps.data.dataVendida) }}
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="perfilOrigem" header="Perfil/Origem" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.perfilOrigem" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column field="email" header="Email" filterField="searchField" :showFilterMenu="true"
        :showFilterMatchModes="false" :showApplyButton="false" :showClearButton="false">
        <template #filter>
          <InputText v-model="searchFilter.email" type="text" placeholder="Pesquisar" />
        </template>
        <template #editor="{ data, field }">
          <InputText v-model="data[field]" @blur="onEdit(data)"></InputText>
        </template>
      </Column>
      <Column header="Ação">
        <template #body="slotProps">
          <div class="d-flex gap-1">
            <Button label="Editar" aria-label="Editar" icon="pi pi-pencil" @click="handleEditButton(slotProps.data)"
              outlined />
            <Button label="Excluir" aria-label="Excluir" icon="pi pi-times"
              @click="handleDeleteButton($event, 1); Object.assign(selected, slotProps.data);" severity="danger"
              outlined />
          </div>
        </template>
      </Column>
    </DataTable>
    <Paginator :totalRecords="localTotalGames" :first="currentFirst" :rowsPerPageOptions="[100, 200, 300]"
      template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown"
      :rows="pagination!.per_page" @page="handlePageChange"></Paginator>
    <p>Total: {{ localTotalGames }}</p>
  </div>
</template>

<style scoped>
.p-datatable {
  font-size: 0.90rem;
}

/* Reduz o espaçamento interno das células */
.p-datatable .p-datatable-tbody>tr>td {
  padding: 0.5rem;
}

/* Reduz o espaçamento interno no cabeçalho */
.p-datatable .p-datatable-thead>tr>th {
  padding: 0.5rem;
}

</style>