<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title class="headline text-uppercase">
        <span>Agnes UI</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <CreateReleaseDialog>Create release</CreateReleaseDialog>
    </v-app-bar>

    <v-content>
      <HelloWorld/>
    </v-content>
  </v-app>
</template>

<script lang="ts">
    import Vue from 'vue';
    import HelloWorld from './components/HelloWorld.vue';
    import CreateReleaseDialog from "@/components/CreateReleaseDialog.vue";

    export default Vue.extend({
        name: 'App',
        components: {
            CreateReleaseDialog,
            HelloWorld,
        },
        data: () => ({
            dialog: false
        }),
    });
</script>
