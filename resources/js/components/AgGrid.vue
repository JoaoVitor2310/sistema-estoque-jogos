<script setup lang="ts">
import { ref } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // CSS obrigatório para o Data Grid
import "ag-grid-community/styles/ag-theme-quartz.min.css"; // Tema opcional aplicado ao Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Componente Data Grid do Vue

const props = defineProps<{
    colDefs: any[], 
    rowData: any[],
    autoSizeStrategy?: SizeColumnsToContentStrategy
}>();

interface SizeColumnsToContentStrategy {
  type: 'fitCellContents';
  // If true, the header won't be included when calculating the column widths. 
  skipHeader?: boolean;
  // If not provided will auto-size all columns. Otherwise will size the specified columns.
  colIds?: string[];
}

const autoSizeStrategy: SizeColumnsToContentStrategy = {
    type: 'fitCellContents',
    skipHeader: false,
}

</script>

<template>
    <AgGridVue :rowData="rowData" :columnDefs="colDefs" style="width: 100%; height: 50vh;" :autoSizeStrategy="autoSizeStrategy" class="ag-theme-quartz">
    </AgGridVue>
</template>