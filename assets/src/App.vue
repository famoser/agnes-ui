<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title class="headline text-uppercase">
        <span>Agnes UI</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        @click="openReleaseWindow"
      >
       Create release
      </v-btn>
    </v-app-bar>

    <v-content>
      <HelloWorld/>
    </v-content>
  </v-app>
</template>

<script lang="ts">
import Vue from 'vue';
import HelloWorld from './components/HelloWorld.vue';


export default Vue.extend({
  name: 'App',
  components: {
    HelloWorld,
  },
  data: () => ({
    //
  }),
});
</script>
