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
            <DeployDialog>Deploy</DeployDialog>
            <RollbackDialog class="ml-2">Rollback</RollbackDialog>
            <CopySharedDialog class="ml-2">Replicate</CopySharedDialog>
            <InstanceOverview class="mt-6"/>
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
  import RollbackDialog from "@/components/RollbackDialog.vue";
  import CopySharedDialog from "@/components/CopySharedDialog.vue";

  export default Vue.extend({
    name: "App",
    components: {
      CopySharedDialog,
      RollbackDialog,
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
