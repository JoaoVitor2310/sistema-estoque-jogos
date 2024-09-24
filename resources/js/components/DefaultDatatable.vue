<script lang="ts">
import DataTable from 'primevue/datatable';


</script>

<template>
    <DataTable :value="props.taxas" stripedRows sortMode="multiple" removableSort
        :globalFilterFields="['nome', 'preco']" v-model:filters="filters" v-model:selection="selectedProduct"
        selectionMode="single" scrollable scrollHeight="100vh" editMode="cell" dataKey="id" size="large"
        tableStyle="min-width: 50rem">
        <h1>a</h1>
    </DataTable>
</template>