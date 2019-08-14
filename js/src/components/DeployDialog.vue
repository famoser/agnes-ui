<template>
  <v-dialog v-model="dialog" persistent max-width="600px">
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
                v-model="target"
                @change=""
                label="target*"
                hint="in the form server:environment:stage, for example *:*:dev"
                persistent-hint
                required
              ></v-text-field>
            </v-flex>
            <v-flex xs12 md6>
              <v-text-field
                v-model="release"
                label="release*"
                required
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <small>*indicates required field</small>
        <small>deploy {{canDeploy ? "yes" : "no"}}</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="dialog = false" :disabled="disabled">Close</v-btn>
        <v-btn color="blue darken-1" text @click="deploy()" :disabled="canDeploy" :loading="disabled">Release</v-btn>
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
    public disabled = false;

    private _deployApi: DeployApi | null = null;
    private _apiError: String | null = null;

    public target: string = "*:*:dev";
    public release: string = "";

    private reloadAffectedInstancesTask: any;

    private _affectedInstances: Instance[] | null = null;

    mounted() {
      this._deployApi = new DeployApi(undefined, process.env.VUE_APP_API_BASE_URL);
      this._affectedInstances = []
    }

    @Watch('target')
    onTargetChanged() {
      this.onReleaseChanged();
    }

    @Watch('release')
    onReleaseChanged() {
      this._affectedInstances = [];

      // debounce reload
      if (this.reloadAffectedInstancesTask) {
        clearTimeout(this.reloadAffectedInstancesTask);
      }
      this.reloadAffectedInstancesTask = setTimeout(() => {
        this.dryRun()
      }, 200);
    }

    dryRun() {
      const deployment = this.createDeployment();

      this._deployApi!!.deployDryRun(deployment).then((instances) => {
        this._affectedInstances = instances.data
      });
    }

    private createDeployment(): Deployment {
      return {
        release: this.release,
        target: this.target
      }
    }

    get canDeploy() {
      return this._affectedInstances != null && this._affectedInstances.length > 0;
    }

    deploy() {
      const deployment = this.createDeployment();

      this._deployApi!!.deploy(deployment)
        .then(() => {
          this.dialog = false
        })
        .catch((reason) => {
          this._apiError = reason;
        });
    }
  }
</script>
