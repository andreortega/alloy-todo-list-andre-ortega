<script setup>
import { ref, watch, defineEmits, defineProps } from 'vue';

const emits = defineEmits(['close', 'save']);
const props = defineProps({
  visible: Boolean,
  task: Object,
});

const form = ref({
  nome: '',
  descricao: '',
  finalizado: false,
  data_limite: null,
});

watch(() => props.task, (newTask) => {
  if (newTask) {
    form.value = { ...newTask };
  } else {
    form.value = { nome: '', descricao: '', finalizado: false, data_limite: null };
  }
});

function onSave() {
  emits('save', form.value);
}
function onClose() {
  emits('close');
}
</script>

<template>
  <div v-show="visible" class="modal-overlay">
    <div class="modal-content">
      <h3>{{ form.nome ? 'Editar Tarefa' : 'Nova Tarefa' }}</h3>
      <form @submit.prevent="onSave">
        <label>Nome</label>
        <input v-model="form.nome" required />
        
        <label>Descrição</label>
        <textarea v-model="form.descricao"></textarea>

        <label>Finalizado</label>
        <input type="checkbox" v-model="form.finalizado" />

        <label>Data limite</label>
        <input type="date" v-model="form.data_limite" />

        <button type="submit">Salvar</button>
        <button type="button" @click="onClose">Cancelar</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex; justify-content: center; align-items: center;
  z-index: 1000;
}
.modal-content {
  background: white; 
  padding: 1rem; 
  border-radius: 8px; 
  width: 400px;
  max-width: 90vw;
}
</style>
