<template>
  <Main title="Ticket List">
    <div class="flex items-center justify-between bg-orange-400 mb-5 py-3 px-2">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="text-white font-medium">Priority:</span>
          <button
              v-for="option in priorityOptions"
              :key="option?.value"
              @click="setFilter('priority', option?.value)"
              :class="[
                'px-3 py-1 rounded-full text-sm font-medium',
                filters?.priority === option?.value
                  ? 'bg-white text-orange-600'
                  : 'bg-orange-300 text-white hover:bg-orange-200'
              ]"
          >
            {{ option?.label }}
          </button>
        </div>

        <div class="flex items-center gap-2">
          <span class="text-white font-medium">Status:</span>
          <button
              v-for="option in statusOptions"
              :key="option?.value"
              @click="setFilter('status', option?.value)"
              :class="[
                'px-3 py-1 rounded-full text-sm font-medium',
                filters?.status === option?.value
                  ? 'bg-white text-orange-600'
                  : 'bg-orange-300 text-white hover:bg-orange-200'
              ]"
          >
            {{ option?.label }}
          </button>
        </div>
      </div>
      <div class="flex gap-5">
        <button
            @click="filters.cursor = pagination?.prev_cursor"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!pagination?.prev_cursor"
        >
          ← Previous
        </button>
        <button
            @click="filters.cursor = pagination?.next_cursor"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="!pagination?.next_cursor"
        >
          Next →
        </button>
      </div>
    </div>

    <div class="flex flex-col gap-5">
      <Ticket v-for="ticket in tickets" :ticket="ticket"/>
    </div>
  </Main>
</template>

<script setup>
import Main from "../layouts/Main.vue";
import {onMounted, ref, watch} from "vue";
import axios from "axios";
import Ticket from "../components/Ticket.vue";

const tickets = ref([])
const pagination = ref([])

const filters = ref({
  priority: null,
  status: null,
  cursor: null,
  limit: 12
})

const priorityOptions = [
  { label: 'All', value: '' },
  { label: 'Low', value: 'low' },
  { label: 'Medium', value: 'medium' },
  { label: 'High', value: 'high' },
]

const statusOptions = [
  { label: 'All', value: '' },
  { label: 'Open', value: 'open' },
  { label: 'Pending', value: 'pending' },
  { label: 'Closed', value: 'closed' },
]
function setFilter(type, value) {
  filters.value[type] = value
  filters.value.cursor = null
}

const getTickets = async (filter) => {
  const {data: payload} = await axios?.get('/api/ticket', {
    params:  filter
  })
  const {data: items, ...paginationInfo} = payload?.data
  tickets.value = items
  pagination.value = paginationInfo
}

watch(filters, function (filter){
  getTickets(filter)
}, {deep: true})

onMounted(() => getTickets(filters.value))
</script>
