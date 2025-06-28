<script setup>
import TasksContainer from '@/components/TasksContainer.vue';
// import Appbar from '@/components/Appbar.vue'; // <--- REMOVER
import Footer from '@/components/Footer.vue';
</script>

<template>
  <div class="content-tasks">
    <!-- <Appbar />  <-- REMOVER ESSA LINHA -->
    <TasksContainer />
    <Footer />
  </div>
</template>
