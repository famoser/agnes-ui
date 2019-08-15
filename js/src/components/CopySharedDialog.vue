<template>
  <v-dialog v-model="dialog" max-width="1000px" @keydown.esc="dialog = false">
    <template v-slot:activator="{ on }">
      <v-btn v-on="on">
        <slot></slot>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Replicate environment</span>
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md pa-0>
          <v-layout wrap>
            <v-flex xs12 md6>
              <v-text-field
                v-model="source"
                :disabled="state === 'copy_shared_active'"
                label="source*"
                hint="in the form server:environment:stage"
                persistent-hint
                required/>
            </v-flex>
            <v-flex xs12 md6>
              <v-text-field
                v-model="target"
                :disabled="state === 'copy_shared_active'"
                label="target*"
                hint="in the form server:environment:stage"
                persistent-hint
                required/>
            </v-flex>
            <v-flex md12 class="mt-6">
              <v-progress-circular v-if="state === 'loading-dry-run'" indeterminate/>

              <v-alert v-if="!anyAffectedInstances && state === 'idle'" type="info">
                No replication with these constrains are possible.
              </v-alert>

              <v-alert v-if="state === 'errored'" type="error">
                The request failed! Ensure your arguments make sense and look in the console for the error.
              </v-alert>

              <v-alert v-if="state === 'copy_shared_errored'" type="error">
                The replication failed! Look in the console for the error.
              </v-alert>
              <v-alert
                v-if="anyAffectedInstances"
                border="top"
                colored-border
                color="primary"
                elevation="2"
              >
                <p>The data from the sources will override all data from the targets</p>
                <v-simple-table class="elevation-1">
                  <thead>
                  <tr>
                    <th colspan="3">source</th>
                    <th></th>
                    <th colspan="3">target</th>
                    <th>release</th>
                  </tr>
                  <tr>
                    <th>server</th>
                    <th>environment</th>
                    <th>stage</th>
                    <th></th>
                    <th>server</th>
                    <th>environment</th>
                    <th>stage</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="acrossInstanceAction in affectedInstances" :key="acrossInstanceAction.name">
                    <td>{{acrossInstanceAction.source.server}}</td>
                    <td>{{acrossInstanceAction.source.environment}}</td>
                    <td>{{acrossInstanceAction.source.stage}}</td>
                    <td>-></td>
                    <td>{{acrossInstanceAction.target.server}}</td>
                    <td>{{acrossInstanceAction.target.environment}}</td>
                    <td>{{acrossInstanceAction.target.stage}}</td>
                    <td>{{acrossInstanceAction.source.currentReleaseName}}</td>
                  </tr>
                  </tbody>
                </v-simple-table>
              </v-alert>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="dialog = false" :disabled="state === 'copy_shared_active'">Close</v-btn>
        <v-btn color="primary" @click="copyShared()" :disabled="!anyAffectedInstances"
               :loading="state === 'copy_shared_active'">
          Deploy
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {
    AcrossInstancesAction, CopyShared,
    CopySharedApi,
    DeployApi,
    Deployment,
    Instance,
    PendingReleaseInstance,
    ReleaseApi,
    Rollback,
    RollbackApi
  } from "@/api/api";

  @Component
  export default class CopySharedDialog extends Vue {
    public dialog = false;

    public state = "initial";

    private copySharedApi = new CopySharedApi(undefined, process.env.VUE_APP_API_BASE_URL);

    public source: string = "*:*:prod";
    public target: string = "*:*:dev";

    private reloadAffectedInstancesTask: any;

    private affectedInstances: AcrossInstancesAction[] = [];

    @Watch('target')
    onTargetChanged() {
      this.affectedInstances = [];

      // debounce reload
      if (this.reloadAffectedInstancesTask) {
        clearTimeout(this.reloadAffectedInstancesTask);
      }
      this.reloadAffectedInstancesTask = setTimeout(() => {
        this.dryRun()
      }, 1000);
    }

    @Watch('source')
    onSourceChanged() {
      this.onTargetChanged();
    }

    mounted() {
      this.dryRun();
    }

    private dryRun() {
      const copyShared = this.createCopyShared();
      this.state = "loading-dry-run";

      this.copySharedApi.copySharedDryRun(copyShared)
        .then((instances) => {
          this.affectedInstances = instances.data;
          this.state = "idle";
        })
        .catch((reason) => {
          this.state = "errored";
          console.log(reason);
        });
    }

    get anyAffectedInstances() {
      return this.affectedInstances.length > 0;
    }

    copyShared() {
      const copyShared = this.createCopyShared();
      this.state = "copy_shared_active";

      this.copySharedApi.copyShared(copyShared)
        .then(() => {
          this.state = "idle";
          this.dialog = false;
        })
        .catch((reason) => {
          this.state = "copy_shared_errored";
          console.log(reason);
        });
    }

    private createCopyShared(): CopyShared {
      return {
        target: this.target,
        source: this.source
      }
    }
  }
</script>
