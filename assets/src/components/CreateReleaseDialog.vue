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
        <small>*indicates required field</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
        <v-btn color="blue darken-1" text @click="createRelease()">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

@Component
export default class CreateReleaseDialog extends Vue {
  public dialog = false;

  public branch: String = "";
  public commit: String = "";
  public releaseNotes = "";
  public name = "";

  mounted() {
    this.branch = this.branches[0];
    this.commit = this.commits[0];
  }

  createRelease() {
    this.dialog = false;
  }

  get branches() {
    return ["master"];
  }

  get commits() {
    return ["latest"];
  }
}
</script>
