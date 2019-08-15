<template>
  <v-dialog v-model="dialog" max-width="600px" @keydown.esc="dialog = false">
    <template v-slot:activator="{ on }">
      <v-btn color="primary" dark v-on="on">
        <slot></slot>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Deploy</span>
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md pa-0>
          <v-layout wrap>
            <v-flex xs12 md6>
              <v-text-field
                v-model="release"
                :disabled="state === 'deploying'"
                label="release*"
                required
              ></v-text-field>
            </v-flex>
            <v-flex xs12 md6>
              <v-text-field
                v-model="target"
                :disabled="state === 'deploying'"
                label="target*"
                hint="in the form server:environment:stage"
                persistent-hint
                required
              ></v-text-field>
            </v-flex>
            <v-flex md12 class="mt-6">
              <v-alert v-if="state === 'initial'" type="info">
                Select release and targets and we will show you a preview which instances are affected.
              </v-alert>
              <v-progress-circular v-if="state === 'loading-dry-run'" class="justify-center" indeterminate/>

              <v-alert v-if="!anyAffectedInstances && state === 'idle'" type="info">
                The release was not found or you are not allowed to deploy the release on any specified targets.
              </v-alert>

              <v-alert v-if="state === 'errored'" type="error">
                The request failed! Ensure your arguments make sense and look in the console for the error.
              </v-alert>
              <v-alert v-if="state === 'deploy_errored'" type="error">
                The deployment failed! Look in the console for the error.
              </v-alert>
              <v-alert
                v-if="anyAffectedInstances"
                border="top"
                colored-border
                color="primary"
                elevation="2"
              >
                <p>your deployment will be executed on the following instances</p>
                <v-simple-table class="elevation-1">
                  <thead>
                  <tr>
                    <th>server</th>
                    <th>environment</th>
                    <th>stage</th>
                    <th>current release</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="instance in affectedInstances" :key="instance.name">
                    <td>{{instance.server}}</td>
                    <td>{{instance.environment}}</td>
                    <td>{{instance.stage}}</td>
                    <td>{{instance.currentReleaseName}}</td>
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
        <v-btn @click="dialog = false" :disabled="state === 'deploying'">Close</v-btn>
        <v-btn color="primary" @click="deploy()" :disabled="!anyAffectedInstances" :loading="state === 'deploying'">
          Deploy
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
  import {Component, Vue, Watch} from "vue-property-decorator";
  import {DeployApi, Deployment, Instance, ReleaseApi} from "@/api/api";

  @Component
  export default class CreateReleaseDialog extends Vue {
    public dialog = false;

    public state = "initial";

    private deployApi = new DeployApi(undefined, process.env.VUE_APP_API_BASE_URL);

    public target: string = "*:*:dev";
    public release: string = "";

    private reloadAffectedInstancesTask: any;

    private affectedInstances: Instance[] = [];

    @Watch('target')
    onTargetChanged() {
      this.onReleaseChanged();
    }

    @Watch('release')
    onReleaseChanged() {
      this.affectedInstances = [];

      // debounce reload
      this.state = 'loading-dry-run';
      if (this.reloadAffectedInstancesTask) {
        clearTimeout(this.reloadAffectedInstancesTask);
      }
      this.reloadAffectedInstancesTask = setTimeout(() => {
        this.dryRun()
      }, 1000);
    }

    private dryRun() {
      const deployment = this.createDeployment();
      this.state = "loading-dry-run";

      this.deployApi.deployDryRun(deployment)
        .then((instances) => {
          this.affectedInstances = instances.data;
          this.state = "idle";
        })
        .catch((error) => {
          this.state = "errored";
          console.log(error.response);
        });
    }

    get anyAffectedInstances() {
      return this.affectedInstances.length > 0;
    }

    deploy() {
      const deployment = this.createDeployment();
      this.state = "deploying";

      this.deployApi.deploy(deployment)
        .then(() => {
          this.dialog = false;
          this.state = "idle";
        })
        .catch((error) => {
          this.state = "deploy_errored";
          console.log(error.response);
        });
    }

    private createDeployment(): Deployment {
      return {
        release: this.release,
        target: this.target
      }
    }
  }
</script>
