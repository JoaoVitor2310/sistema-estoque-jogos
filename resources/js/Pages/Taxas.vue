<script setup lang="ts">
import AgGrid from '@/components/AgGrid.vue';
import type { ApiErrorResponse } from '@/types/ApiErrorResponse';
import type { ApiOkResponse } from '@/types/ApiOkResponse';
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
const apiUrl = import.meta.env.VITE_API_URL;

// Definindo as colunas da tabela
const colDefs = ref([
  { field: "id", headerName: "ID" },
  { field: "nome", headerName: "Nome" },
  { field: "preco", headerName: "Preço" },
  { field: "action", headerName: "Ação" },
]);

// Definindo os dados da tabela
let rowData: any[] = reactive([]);

const props = defineProps({ taxas: Array });
console.log(props.taxas)
Object.assign(rowData, props.taxas);

</script>

<template>
  <AgGrid :col-defs="colDefs" :row-data="rowData"></AgGrid>
</template>
