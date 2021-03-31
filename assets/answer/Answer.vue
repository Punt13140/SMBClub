<template>
  <div>
    <div v-if="answers.length">
      <div style="border: 1px solid #000000" v-for="f in answers" :key="f['@id']">


        <!--        <section id="user">
                  <user-post :api-link="f.postedBy"></user-post>
                </section>-->


        <p v-html="f.content">
        </p>
      </div>


      <div class="pagination_ans">
        <a id="page1" href="#">1</a>
        ...
        <a id="lastpage" href="#" v-html="nbPage"></a>
      </div>
    </div>
    <p v-else>Pas de réponses.</p>

    <p v-if="sent">Merci pour votre réponse.</p>
    <form v-else @submit.prevent="onSubmit">
      <label>
        <textarea v-model="content" name="content" placeholder="Réponse rapide..."></textarea>
      </label>
      <input :disabled="!content" type="submit" value="Post">
    </form>
  </div>
</template>


<script>
import UserPost from "./UserPost";

export default {
  components: {UserPost},
  props: ['topicId', 'lastPage', 'nbPage'],
  data() {
    return {answers: [], content: '', sent: false};
  },
  created() {
    this.fetchAnswers();
    this.fetchUsers();
  },

  methods: {

    fetchAnswers() {
      fetch(`/api/topics/${this.topicId}/answers?page=1`)
          .then(response => response.json())
          .then(data => this.answers = data['hydra:member']);
    },
    fetchUsers() {
      console.log("fetchUsers");
      console.log("-----> " + this.answers.length);

      for (const element of this.answers) {
        console.log(element);
      }
    },
    onSubmit() {
      const {topicId, content} = this;
      fetch('/api/answers', {
        method: 'POST',
        headers: {'Content-Type': 'application/ld+json'},
        body: JSON.stringify({topic: `/api/topics/${topicId}`, content})
      })
          .then(() => {
            this.sent = true;
            this.fetchAnswers();
          })
    }
  }
}


</script>

<style scoped></style>