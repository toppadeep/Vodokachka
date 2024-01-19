<script>
export default {
  props: {
    errors: {
      type: [ Array, Object ],
      required: true
    },
    field: {
      type: [String, Number, Date],
      required: true
    }
  }
}
</script>

<template>
  <ul v-if="errors[field]" class="errorMessage">
    <li v-for="(err, index) of errors[field]" :key="index">{{ err }}</li>
  </ul>
</template>
