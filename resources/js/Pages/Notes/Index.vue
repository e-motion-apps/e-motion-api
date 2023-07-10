<script setup>
import { defineProps, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  notes: Array,
});

const noteText = ref('');

const saveNotes = () => {
  const noteValues = props.notes.map((note) => note.text);
  Inertia.post('/notes', { noteText: noteValues });
}

const addNote = () => {
  if (noteText.value) {
    props.notes.push({ text: noteText.value });
    noteText.value = '';
    saveNotes();
  }
}

const deleteNote = (index) => {
  props.notes.splice(index, 1);
  saveNotes();
};
</script>


<template>
    <div>
      <div class="note-container flex mb-4">
        <div class="relative w-3/4">
          <textarea v-model="noteText" class="note-input w-full h-24 px-2 py-1" placeholder="Write your note here"></textarea>
          <button class="note-button rounded bg-green-500 px-3 py-3 font-semibold text-white text-sm absolute top-1/2 right-2 transform -translate-y-1/2" @click="addNote">
            Add Note
          </button>
        </div>
      </div>
  
      <ul class="note-list">
        <li v-for="(note, index) in props.notes" :key="index" class="note-item mb-4">
          <div class="border border-gray-300 bg-gray-100 p-4 rounded-lg w-1/4 relative">
            <p class="grow">{{ note }}</p>
            <button class="note-delete rounded bg-red-500 px-3 py-1 font-semibold text-white mt-2 text-sm absolute top-2 right-2" @click="deleteNote(index)">
              Delete
            </button>
          </div>
        </li>
      </ul>
    </div>
  </template>
  