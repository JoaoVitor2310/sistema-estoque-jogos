<script setup lang="ts">
// import type { ApiErrorResponse } from '@/types/ApiErrorResponse';
// import type { ApiOkResponse } from '@/types/ApiOkResponse';
// import type { GameLine } from '@/types/GameLine';
import axios from 'axios';
import DatePicker from 'primevue/datepicker';
import { onMounted, reactive, ref } from 'vue';
// const apiUrl = import.meta.env.VITE_API_URL;

// Definindo as colunas da tabela
const colDefs = ref([
    { field: "id", headerName: "ID" },
    { field: "fornecedor.quantidade_reclamacoes", headerName: "Qtd. Reclamações" },
    { field: "tipo_reclamacao.nome", headerName: "Reclamação" },
    { field: "steamId", headerName: "SteamID" },
    { field: "tipo_formato.nome", headerName: "Formato" },
    { field: "chaveRecebida", headerName: "Chave Recebida?" },
    { field: "nomeJogo", headerName: "Nome do Jogo" },
    { field: "precoJogo", headerName: "Preço do Jogo" },
    { field: "notaMetacritic", headerName: "Nota Metacritic" },
    { field: "isSteam", headerName: "Nota Steam?" },
    { field: "randomClassificationG2A", headerName: "Classificação G2A" },
    { field: "randomClassificationKinguin", headerName: "Classificação Kinguin" },
    { field: "observacao", headerName: "Observação" },
    { field: "leilao_g2_a.nome", headerName: "Leilão G2A" },
    { field: "leilao_gamivo.nome", headerName: "Leilão Gamivo" },
    { field: "leilao_kinguin.nome", headerName: "Leilão Kinguin" },
    { field: "plataforma.nome", headerName: "Plataforma" },
    { field: "precoCliente", headerName: "Preço Cliente" },
    { field: "precoVenda", headerName: "Preço de Venda" },
    { field: "incomeReal", headerName: "Lucro Real" },
    { field: "incomeSimulado", headerName: "Lucro Simulado" },
    { field: "chaveEntregue", headerName: "Chave Entregue?" },
    { field: "valorPagoTotal", headerName: "Valor Pago Total" },
    { field: "valorPagoIndividual", headerName: "Valor Pago Individual" },
    { field: "vendido", headerName: "Vendido?" },
    { field: "leiloes", headerName: "Número de Leilões" },
    { field: "quantidade", headerName: "Qtd." },
    { field: "devolucoes", headerName: "Devoluções" },
    { field: "lucroRS", headerName: "Lucro R$" },
    { field: "lucroPercentual", headerName: "Lucro (%)" },
    { field: "dataAdquirida", headerName: "Data Adquirida" },
    { field: "dataVenda", headerName: "Data de Venda" },
    { field: "dataVendida", headerName: "Data Vendida" },
    { field: "fornecedor.perfilOrigem", headerName: "Perfil/Origem" },
    { field: "email", headerName: "Email" },
]);

// Definindo os dados da tabela
// const rowData = ref<GameLine[]>([
//     {
//         id: 1, 
//         reclamacoesAnteriores: 1,
//         reclamacao: "dup",
//         SteamID: '64950',
//         formato: 'RK',
//         ChaveRecebida: "Sim",
//         nomeJogo: "Cyberpunk 2077",
//         precoDoJogo: 59.99,
//         NotaMetacritic: 85,
//         Steam: true,
//         ClassificacaoRandomG2A: "Gold",
//         Observacao: "Problemas na chave anterior",
//         LeilaoG2A: "Sim",
//         LeilaoGamivo: "Não",
//         LeilaoKinguin: "Sim",
//         Plataforma: "PC",
//         PrecoCliente: 49.99,
//         PrecoVenda: 59.99,
//         IncomeReal: 10.00,
//         IncomeSimulado: 9.50,
//         ChaveEntregue: "Sim",
//         ValorPagoTotal: "50.00",
//         ValorPagoIndividual: "50.00",
//         Vendido: true,
//         Leiloes: 3,
//         Qtd: 1,
//         Devolucoes: 0,
//         LucroReal: 10.00,
//         LucroPercentual: 20.00,
//         DataAdquirida: "2024-01-01",
//         DataVenda: "2024-02-01",
//         DataVendida: "2024-02-02",
//         PerfilOrigem: "Perfil Steam",
//         email: "cliente1@email.com"
//     },
// ]);

// const rowData = reactive<GameLine[]>([]);

// onMounted(async (): Promise<void> => {
//     const res = await axios.get<ApiOkResponse | ApiErrorResponse>(`${apiUrl}/api/games?limit=100&offset=0`);
//     Object.assign(rowData, res.data.data);
//     console.log(rowData);
// })

// import PrimeVue from 'primevue/config';
</script>

<template>
  <h1>Venda chave troca</h1>
</template>
