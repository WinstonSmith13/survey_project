<template>
  <fieldset class="mb-4">
    <!--Question Title-->
    <div>
      <legend class="text-base font-medium text-gray-900">
        {{ index + 1 }}. {{ question.question }}
      </legend>
      <p class="text-gray-500 text-sm">
        {{ question.description }}
      </p>
    </div>
    <div class="mt-3">
      <div v-if="question.type === 'select'">
        <select
          :value="modelValue"
          @change="emits('update:modelValue', $event.target.value)"
          class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
        >
          <option value="">
            Selectionner votre réponse ...
          </option>
          <option
            v-for="option in question.data.options"
            :key="option.uuid"
            :value="option.text"
          >
            {{ option.text }}
          </option>
        </select>
      </div>

      <div v-else-if="question.type === 'radio'">
        <div
          v-for="(option, ind) of question.data.options"
          :key="option.uuid"
          class="flex items-center"
        >
          <!--:name permet de selectionner qu'un seul choix. -->
          <input
            :id="option.uuid"
            :name="'question' + question.id"
            :value="option.text"
            @change="emits('update:modelValue', $event.target.value)"
            type="radio"
            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
          >
          <label
            :for="option.uuid"
            class="ml-3 block text-sm font-medium text-gray-700"
          >
            {{ option.text }}
          </label>
        </div>
      </div>

      <div v-else-if="question.type === 'checkbox'">
        <pre>{{ model }}</pre>
        <div
          v-for="(option, ind) of question.data.options"
          :key="option.uuid"
          class="flex items-center"
        >
          <input
            :id="option.uuid"
            v-model="model[option.text]"
            @change="onCheckboxChange"
            type="checkbox"
            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
          >
          <label
            :for="option.uuid"
            class="ml-3 block text-sm font-medium text-gray-700"
          >
            {{ option.text }}
          </label>
        </div>
      </div>
      <div v-else-if="question.type === 'text'">
        <input
          type="text"
          :value="modelValue"
          @input="emits('update:modelValue', $event.target.value)"
          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        >
      </div>
      <div v-else-if="question.type === 'textarea'">
        <textarea
          :value="modelValue"
          @input="emits('update:modelValue', $event.target.value)"
          class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        />
      </div>
    </div>
  </fieldset>
  <hr class="mb-4">
</template>

<script setup>
import { ref } from 'vue'

// Ce sont les props qui sont passés par la page SurveyPublic
const { question, index, modelValue } = defineProps({
  question: Object,
  index: Number,
  modelValue: [String, Array]
})

const emits = defineEmits(['update:modelValue'])

// model est utilisé pour le checkbox type, checkbox peut avoir plusieur réponse.
let model
if (question.type === 'checkbox') {
  model = ref({})
}

function shouldHaveOptions () {
  return ['select', 'radio', 'checkbox'].includes(question.type)
}

function onCheckboxChange ($event) {
  const selectedOptions = []
  for (const text in model.value) {
    if (model.value[text]) {
      selectedOptions.push(text)
    }
  }
  emits('update:modelValue', selectedOptions)
}
</script>

  <style></style>
