<template>
  <div>
    <the-header :auth="auth" />
    <router-view :errors="errors" :auth="auth"/>
    <the-footer />
  </div>
</template>

<script>
import TheHeader from '../layout/TheHeader'
import TheFooter from '../layout/TheFooter'

export default {
  name: 'app',
  components: {
    TheHeader,
    TheFooter
  },
  props: {
    auth: {
      type: Object|Array
    },
    errors: {
      type: Object|Array
    }
  }
}
</script>