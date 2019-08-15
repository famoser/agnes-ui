<template>
  <v-dialog v-model="dialog" max-width="600px" @keydown.esc="dialog = false">
    <template v-slot:activator="{ on }">
      <v-btn v-on="on">
        <slot></slot>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Rollback</span>
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md pa-0>
          <v-layout wrap>
            <v-flex xs12 md6>
              <v-text-field
                v-model="target"
                :disabled="state === 'rollback_active'"
                label="target*"
                hint="in the form server:environment:stage"
                persistent-hint
                required/>
            </v-flex>
            <v-flex xs6 md3>
              <v-text-field
                v-model="rollbackTo"
                :disabled="state === 'rollback_active'"
                label="only rollback to"
                required/>
            </v-flex>
            <v-flex xs6 md3>
              <v-text-field
                v-model="rollbackFrom"
                :disabled="state === 'rollback_active'"
                label="only rollback from"
                required/>
            </v-flex>
            <v-flex md12 class="mt-6">
              <v-progress-circular v-if="state === 'loading-dry-run'" indeterminate/>

              <v-alert v-if="!anyAffectedInstances && state === 'idle'" type="info">
                No rollback with these constrains are possible.
              </v-alert>

              <v-alert v-if="state === 'errored'" type="error">
                The request failed! Ensure your arguments make sense and look in the console for the error.
              </v-alert>
              <v-alert v-if="state === 'rollback_errored'" type="error">
                The rollback failed! Look in the console for the error.
              </v-alert>
              <v-alert
                v-if="anyAffectedInstances"
                border="top"
                colored-border
                color="primary"
                elevation="2"
              >
                <p>the rollback will be executed on the following instances</p>
                <v-simple-table class="elevation-1">
                  <thead>
                  <tr>
                    <th>server</th>
                    <th>environment</th>
                    <th>stage</th>
                    <th>current release</th>
                    <th>release after rollback</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="pendingReleaseInstance in affectedInstances" :key="pendingReleaseInstance.name">
                    <td>{{pendingReleaseInstance.instance.server}}</td>
                    <td>{{pendingReleaseInstance.instance.environment}}</td>
                    <td>{{pendingReleaseInstance.instance.stage}}</td>
                    <td>{{pendingReleaseInstance.instance.currentReleaseName}}</td>
                    <td>{{pendingReleaseInstance.pendingReleaseName}}</td>
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
        <v-btn @click="dialog = false" :disabled="state === 'rollback_active'">Close</v-btn>
        <v-btn color="primary" @click="rollback()" :disabled="!anyAffectedInstances"
               :loading="state === 'rollback_active'">
          Deploy
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {DeployApi, Deployment, Instance, PendingReleaseInstance, ReleaseApi, Rollback, RollbackApi} from "@/api/api";

  @Component
  export default class RollbackDialog extends Vue {
    public dialog = false;

    public state = "initial";

    private rollbackApi = new RollbackApi(undefined, process.env.VUE_APP_API_BASE_URL);

    public target: string = "*:*:dev";
    public rollbackTo: string = "";
    public rollbackFrom: string = "";

    private reloadAffectedInstancesTask: any;

    private affectedInstances: PendingReleaseInstance[] = [];

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

    @Watch('rollbackTo')
    onRollbackToChanged() {
      this.onTargetChanged();
    }

    @Watch('rollbackFrom')
    onRollbackFromChanged() {
      this.onTargetChanged();
    }

    mounted() {
      this.dryRun();
    }

    private dryRun() {
      const rollback = this.createRollback();
      this.state = "loading-dry-run";

      this.rollbackApi.rollbackDryRun(rollback)
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

    rollback() {
      const rollback = this.createRollback();
      this.state = "rollback_active";

      this.rollbackApi.rollback(rollback)
        .then(() => {
          this.state = "idle";
          this.dialog = false;
        })
        .catch((reason) => {
          this.state = "rollback_errored";
          console.log(reason);
        });
    }

    private createRollback(): Rollback {
      return {
        target: this.target,
        rollbackFrom: this.rollbackFrom,
        rollbackTo: this.rollbackTo
      }
    }
  }
</script>
