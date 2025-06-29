<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: Object,
  loading: Boolean
});
const emit = defineEmits(['update:modelValue', 'submit']);

const nome = ref(props.modelValue?.nome || '');
const descricao = ref(props.modelValue?.descricao || '');
const dataLimite = ref(props.modelValue?.data_limite || '');
const erroNome = ref('');

watch(() => props.modelValue, (nova) => {
  nome.value = nova?.nome || '';
  descricao.value = nova?.descricao || '';
  dataLimite.value = nova?.data_limite || '';
});

function submitForm() {
  erroNome.value = '';
  if (!nome.value.trim()) {
    erroNome.value = 'O nome da tarefa é obrigatório.';
    return;
  }
  emit('submit', {
    nome: nome.value,
    descricao: descricao.value,
    data_limite: dataLimite.value
  });
}
</script>

<template>
  <form @submit.prevent="submitForm" class="flex flex-col gap-5">
    <div>
      <label class="block text-sm font-semibold mb-1">Nome <span class="text-red-500">*</span></label>
      <input v-model="nome" type="text"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-white shadow-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition"
        :disabled="loading" placeholder="Título da tarefa" />
      <div v-if="erroNome" class="text-red-600 text-xs mt-1">{{ erroNome }}</div>
    </div>
    <div>
      <label class="block text-sm font-semibold mb-1">Descrição</label>
      <textarea v-model="descricao"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-white shadow-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition"
        :disabled="loading" rows="2" placeholder="Descrição detalhada (opcional)"></textarea>
    </div>
    <div>
      <label class="block text-sm font-semibold mb-1">Data Limite</label>
      <input v-model="dataLimite" type="date"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-white shadow-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition"
        :disabled="loading" :min="new Date().toISOString().split('T')[0]" />
    </div>
    <button
      class="bg-green-500 text-white px-7 py-3 rounded-full font-semibold hover:bg-green-600 transition disabled:opacity-50 shadow"
      :disabled="loading"
      type="submit"
    >
      <span v-if="loading">Salvando...</span>
      <span v-else>Salvar</span>
    </button>
  </form>
</template>
