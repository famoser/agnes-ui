<template>
  <v-app>
    <v-app-bar app>
      <v-toolbar-title class="headline text-uppercase">
        <span>{{appTitle}}</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <CreateReleaseDialog>Create release</CreateReleaseDialog>
    </v-app-bar>

    <v-content>
      <v-container>
        <v-row class="mb-12"
               justify="center">
          <v-col lg="8">
            <InstanceOverview/>
            <DeployDialog>Deploy</DeployDialog>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script lang="ts">
  import Vue from "vue";
  import CreateReleaseDialog from "@/components/CreateReleaseDialog.vue";
  import InstanceOverview from "@/components/InstanceOverview.vue";
  import DeployDialog from "@/components/DeployDialog.vue";

  export default Vue.extend({
    name: "App",
    components: {
      DeployDialog,
      InstanceOverview,
      CreateReleaseDialog
    },
    data: () => ({
      dialog: false,
      appTitle: process.env.VUE_APP_TITLE
    })
  });
</script>
