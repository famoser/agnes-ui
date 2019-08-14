<template>
  <v-dialog v-model="dialog" persistent max-width="600px">
    <template v-slot:activator="{ on }">
      <v-btn color="primary" dark v-on="on">
        <slot></slot>
      </v-btn>
    </template>
    <v-card>
      <v-card-title>
        <span class="headline">Create release</span>
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md pa-0>
          <v-layout wrap>
            <v-flex xs12 md6>
              <v-select
                v-model="branch"
                :items="branches"
                label="Branch*"
                required
              ></v-select>
            </v-flex>
            <v-flex xs12 md6>
              <v-select
                v-model="commit"
                :items="commits"
                label="commit*"
                required
              ></v-select>
            </v-flex>
            <v-flex xs12>
              <v-textarea
                v-model="releaseNotes"
                label="release notes*"
                required
              ></v-textarea>
            </v-flex>
            <v-flex xs12>
              <v-text-field
                v-model="name"
                label="name*"
                required
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-container>
        <small class="mb-6">*indicates required field</small>

        <v-alert v-if="state === 'failed'" type="error">
          The release failed to publish! Look in the console for the error.
        </v-alert>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn @click="dialog = false" :disabled="state === 'releasing'">Close</v-btn>
        <v-btn color="blue" @click="createRelease()" :loading="state === 'releasing'">Release</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";
    import {ReleaseApi} from "@/api/api";

    @Component
    export default class CreateReleaseDialog extends Vue {
        public dialog = false;
        public state = "initial";

        private readonly latestCommit = "latest";

        public branch: string = "";
        public commit: string = "";
        public releaseNotes = "";
        public name = "";

        mounted() {
            this.branch = this.branches[0];
            this.commit = this.commits[0];
        }

        createRelease() {
            this.state = 'releasing';

            const commitish = this.commit === this.latestCommit ? this.branch : this.commit;

            let release = new ReleaseApi(undefined, process.env.VUE_APP_API_BASE_URL);
            const response = release.add({
                name: this.name,
                commitish: commitish,
                description: this.releaseNotes
            }).then(() => {
              this.dialog = false
            }).catch((reason) => {
              this.state = 'failed';
              console.log(reason)
            });
        }

        get branches() {
            return ["master"];
        }

        get commits() {
            return [this.latestCommit];
        }
    }
</script>
