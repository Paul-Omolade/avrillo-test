<template>
    <div class="min-h-screen bg-gray-50 p-8">
        <div class="mx-auto max-w-3xl rounded-2xl bg-white p-8 shadow-sm">
            <h1 class="text-3xl font-bold">Stamp Duty Calculator</h1>
            <p class="mt-2 text-gray-600">
                Estimate Stamp Duty Land Tax for a residential property purchase in England.
            </p>

            <form class="mt-8 space-y-6" @submit.prevent="calculate">
                <div>
                    <label class="mb-2 block text-sm font-medium">Property price (£)</label>
                    <input v-model="form.price" type="number" min="1" step="1"
                        class="w-full rounded-lg border border-gray-300 p-3" placeholder="e.g. 350000"
                        @input="clearResult" />
                    <p v-if="errors.price" class="mt-2 text-sm text-red-600">{{ errors.price }}</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">Purchase date</label>
                    <input v-model="form.purchase_date" type="date" class="w-full rounded-lg border border-gray-300 p-3"
                        @input="clearResult" />
                    <p v-if="errors.purchase_date" class="mt-2 text-sm text-red-600">{{ errors.purchase_date }}</p>
                </div>

                <label class="flex items-center gap-3">
                    <input v-model="form.is_first_time_buyer" type="checkbox" @change="clearResult" />
                    <span>I’m a first-time buyer</span>
                </label>

                <label class="flex items-center gap-3">
                    <input v-model="form.is_additional_property" type="checkbox" @change="clearResult" />
                    <span>This is an additional property</span>
                </label>

                <button type="submit" :disabled="loading"
                    class="rounded-lg bg-black px-5 py-3 text-white disabled:opacity-50">
                    {{ loading ? 'Calculating...' : 'Calculate SDLT' }}
                </button>
            </form>

            <div v-if="apiError" class="mt-6 rounded-lg bg-red-50 p-4 text-red-700">
                {{ apiError }}
            </div>

            <div v-if="result" class="mt-8 rounded-2xl border border-gray-200 p-6">
                <p class="text-lg text-gray-700">
                    <strong>{{ headlineText }}</strong>
                </p>

                <p class="mt-3 text-5xl font-bold text-black">
                    £{{ formatMoney(result.total) }}
                </p>

                <p class="mt-4 text-base text-gray-700">
                    Based on purchase date of
                    <strong>{{ formattedPurchaseDate }}</strong>
                </p>

                <p class="mt-2 text-base text-gray-700">
                    Effective tax rate is
                    <strong>{{ Number(result.effective_rate).toFixed(2) }}%</strong>
                </p>

                <div v-if="result.notes?.length" class="mt-5 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800">
                    <p class="font-semibold">Important information</p>
                    <ul class="mt-2 list-disc pl-5">
                        <li v-for="note in result.notes" :key="note">{{ note }}</li>
                    </ul>
                </div>

                <div class="mt-8">
                    <h2 class="text-xl font-bold">How this was calculated</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Stamp Duty is charged in bands. That means different parts of the property price
                        are taxed at different rates. The breakdown below shows how much of your purchase
                        falls into each band and how much tax is due for that part.
                    </p>

                    <div class="mt-4 space-y-3">
                        <div v-for="item in result.breakdown" :key="item.label" class="rounded-lg bg-gray-50 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="max-w-2xl">
                                    <p class="font-semibold text-gray-900">{{ item.label }}</p>

                                    <p class="mt-1 text-sm leading-6 text-gray-700">
                                        <template v-if="item.rate === 0">
                                            The portion of your purchase price in this band is
                                            <strong>£{{ formatMoney(item.taxable_amount) }}</strong>.
                                            This part is charged at
                                            <strong>0%</strong>,
                                            so no Stamp Duty is due for this band.
                                        </template>

                                        <template v-else>
                                            The portion of your purchase price in this band is
                                            <strong>£{{ formatMoney(item.taxable_amount) }}</strong>.
                                            This part is charged at
                                            <strong>{{ item.rate }}%</strong>,
                                            which gives
                                            <strong>£{{ formatMoney(item.tax) }}</strong>
                                            in Stamp Duty for this band.
                                        </template>
                                    </p>
                                </div>

                                <div class="text-base font-bold text-gray-900 sm:text-right">
                                    £{{ formatMoney(item.tax) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-xl bg-blue-50 p-4 text-sm text-blue-900">
                    <p class="font-semibold">What this means</p>
                    <p class="mt-2">
                        This is an estimate of the Stamp Duty Land Tax due based on the details you entered.
                        It is intended to help you understand the likely tax cost before proceeding.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios'
import { computed, reactive, ref } from 'vue'

const today = new Date().toISOString().split('T')[0]

const form = reactive({
    price: '',
    purchase_date: today,
    is_first_time_buyer: false,
    is_additional_property: false,
})

const result = ref(null)
const loading = ref(false)
const apiError = ref('')
const errors = reactive({
    price: '',
    purchase_date: '',
})

const clearResult = () => {
    result.value = null
    apiError.value = ''
    errors.price = ''
    errors.purchase_date = ''
}

const formatMoney = (value) => {
    return Number(value).toLocaleString('en-GB')
}

const formatDate = (value) => {
    if (!value) return ''
    const date = new Date(value)
    return date.toLocaleDateString('en-GB')
}

const formattedPurchaseDate = computed(() => formatDate(form.purchase_date))

const headlineText = computed(() => {
    if (!result.value) return ''

    if (result.value.applied_scenario === 'additional_property') {
        return 'Stamp duty on your additional property is'
    }

    if (result.value.applied_scenario === 'first_time_buyer') {
        return 'Stamp duty on your first home is'
    }

    return 'Stamp duty on this property is'
})

const calculate = async () => {
    loading.value = true
    apiError.value = ''
    errors.price = ''
    errors.purchase_date = ''
    result.value = null

    try {
        const payload = {
            price: form.price === '' ? '' : Number(form.price),
            purchase_date: form.purchase_date,
            is_first_time_buyer: !!form.is_first_time_buyer,
            is_additional_property: !!form.is_additional_property,
        }

        const { data } = await axios.post('/api/sdlt/calculate', payload)
        result.value = data
    } catch (error) {
        if (error.response?.status === 422) {
            const validationErrors = error.response.data.errors || {}
            errors.price = validationErrors.price?.[0] || ''
            errors.purchase_date = validationErrors.purchase_date?.[0] || ''
        } else {
            apiError.value = 'Something went wrong while calculating SDLT.'
        }
    } finally {
        loading.value = false
    }
}
</script>
