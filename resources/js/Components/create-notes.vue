<script setup>
import { ref, watch, onMounted, reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

const noteText = ref('')
const notes = ref([])

const state = reactive({
  items: [],
})

onMounted(() => {
  // notes.value = JSON.parse(JSON.stringify($page.props.notes))
  fetchUserNotes()
})

onMounted(async () => {
  const response = await inertia.get('/notes')
  state.items = response.data
})

watch(notes, () => {
  saveNotes()
}, { deep: true })

const saveNotes = () => {
  Inertia.post('/notes', { noteText: notes.value })
}

const fetchUserNotes = () => {
  Inertia.get('/notes')
    .then((response) => {
      notes.value = response.props.notes
    })
   
}

const addNote = () => {
  if (noteText.value) {
    notes.value.push(noteText.value)
    noteText.value = ''
    saveNotes()
  }
}

const deleteNote = (index) => {
  notes.value.splice(index, 1)
  saveNotes()
}

//const displayNote = (index) => {
//Inertia.get(notes.value[index]);
//};

</script>

<template>
  <div>
    <div class="note-container">
      <textarea v-model="noteText" class="note-input mr-2 w-full px-2 py-1" placeholder="Write your note here" />
      <button class="note-button rounded bg-green-500 px-4 py-2 font-semibold text-white" @click="addNote">
        Add Note
      </button>
    </div>

    <ul class="note-list">
      <li v-for="(note, index) in notes" :key="index" class="note-item mb-4 flex items-center">
        <p class="grow">
          {{ note }}
        </p>
        <button class="note-delete rounded bg-red-500 px-3 py-1 font-semibold text-white" @click="deleteNote(index)">
          Delete
        </button>
      </li>
      <button class="note-display rounded bg-blue-500 px-2 py-3 font-semibold text-white" @click="fetchUserNotes">
        note-display
      </button>
    </ul>
  </div>
</template>
