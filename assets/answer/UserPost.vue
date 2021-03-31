<template>
  <div>
    <p v-if="user.length">

    </p>
  </div>
</template>


<script>
export default {
  props: ['apiLink'],
  data() {
    return {user: []};
  },
  created() {
    this.fetchUser();
  },

  methods: {
    fetchUser() {
      fetch(`${this.apiLink}`)
          .then(response => response.json())
          .then(data => console.log(data['hydra:member']));
    }
  }
}


</script>

<style scoped></style>