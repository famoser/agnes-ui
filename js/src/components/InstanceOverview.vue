<template>
  <v-container>
    <v-layout text-center wrap>
      <v-flex mb-4>
        <v-data-table
          :headers="headers"
          :items="items"
          :loading="instances === null"
          hide-default-footer
          class="elevation-1"
        />
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script lang="ts">
  import {Component, Prop, Vue} from "vue-property-decorator";
  import {Instance, InstanceApi} from "@/api/api";

  class Environment {
    private _releaseLookup = new Map<String, String>();

    constructor(public name: String) {
    }

    public setRelease(stage: String, release?: String) {
      if (release != null) {
        this._releaseLookup.set(stage, release);
      }
    }

    public getReleaseByStage(stage: String): String {
      return this._releaseLookup.has(stage) ? this._releaseLookup.get(stage)!! : "";
    }
  }

  @Component
  export default class InstanceOverview extends Vue {

    private instanceApi: InstanceApi | null = null;
    public instances: Array<Instance> | null = null;

    mounted() {
      this.instanceApi = new InstanceApi(undefined, process.env.VUE_APP_API_BASE_URL);
      this.instanceApi.getAll().then((instances) => {
        this.instances = instances.data;
      })
    }

    get headers() {
      let environmentHeader = {
        text: "environment",
        value: "environment"
      };
      return [environmentHeader]
        .concat(this.stages.map(entry => {
          return {
            text: entry,
            value: entry
          }
        }));
    }

    get items() {
      if (this.instances == null) {
        return [];
      }

      let rows = [];
      let environments = new Map<String, Environment>();
      for (const instance of this.instances) {
        if (!environments.has(instance.environment)) {
          const environment = new Environment(instance.environment);
          environments.set(instance.environment, environment);
        }

        let environment = environments.get(instance.environment)!!;
        environment.setRelease(instance.stage, instance.currentReleaseName);
      }

      for (const environment of environments.values()) {
        let row = new Map<String, String>();
        row.set("environment", environment.name);
        for (const stage of this.stages) {
          row.set(stage, environment.getReleaseByStage(stage))
        }

        rows.push(Object.fromEntries(row))
      }

      return rows;
    }

    get stages() {
      if (this.instances == null) {
        return [];
      }

      let stages = this.instances.map(i => i.stage);
      return [...new Set(stages)];
    }
  }
</script>
