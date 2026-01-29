<template>
  <div style="padding: 2rem">
    <h1 data-testid="title">eBay Inventory Sync</h1>

    <p data-testid="status" style="margin: 8px 0;">
      Status:
      <strong v-if="connected">Connected</strong>
      <strong v-else>Not connected</strong>
      <span v-if="connected && env"> ({{ env }})</span>
    </p>

    <div style="display:flex; gap: 12px; margin: 12px 0;">
      <button
        v-if="!connected"
        data-testid="connect-btn"
        @click="connect"
      >
        Connect eBay
      </button>

      <button
        data-testid="sync-btn"
        @click="sync"
        :disabled="loading || !connected"
      >
        {{ loading ? 'Syncing...' : 'Sync Inventory' }}
      </button>
    </div>

    <p v-if="error" data-testid="error" style="color:#ff6b6b">{{ error }}</p>

    <ul v-if="items.length" data-testid="items" style="padding-left: 18px">
      <li v-for="it in items" :key="it.sku" class="inventory-item">
        <strong>{{ it.sku }}</strong> — {{ it.title }} ({{ it.quantity }})
      </li>
    </ul>

    <p v-else data-testid="empty">No inventory loaded yet.</p>
  </div>
</template>

<script setup>
import axios from 'axios'
import { ref } from 'vue'

const items = ref([])
const loading = ref(false)
const error = ref('')

const connected = ref(false)
const env = ref('')

const connect = () => {
  window.location.href = '/ebay/connect'
}

const loadStatus = async () => {
  const res = await axios.get('/api/ebay/status')
  connected.value = !!res.data?.connected
  env.value = res.data?.env || ''
}
  
const loadInventory = async () => {
  const res = await axios.get('/api/ebay/inventory')
  items.value = res.data?.data || []
}

const sync = async () => {
  error.value = ''
  loading.value = true
  try {
    await axios.post('/api/ebay/sync')
    await loadInventory()
  } catch (e) {
    error.value = e?.response?.data?.message || e?.message || 'Failed to sync'
  } finally {
    loading.value = false
  }
}

;(async () => {
  try {
    await loadStatus()
    if (connected.value) await loadInventory()
  } catch {
    connected.value = false
    env.value = ''
    items.value = []
  }
})()
</script>
