<template>
  <div>
    <the-header :auth="auth" />
    <router-view/>
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
  }
}
</script>