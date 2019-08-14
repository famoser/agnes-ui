<template>
  <v-layout wrap>
    <v-flex mb-4>
      <v-simple-table
        class="elevation-1"
      >
        <thead>
        <tr>
          <th></th>
          <th v-for="stage in stages" :key="stage">{{stage}}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="environment in environments" :key="environment.name">
          <td>{{environment.name}}</td>
          <td v-for="stage in stages" :key="environment.name + '_' + stage">
            {{environment.getReleaseByStage(stage)}}
          </td>
        </tr>
        </tbody>
      </v-simple-table>
    </v-flex>
  </v-layout>
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

    private _instanceApi: InstanceApi | null = null;
    public instances: Array<Instance> | null = null;

    mounted() {
      this._instanceApi = new InstanceApi(undefined, process.env.VUE_APP_API_BASE_URL);
      this._instanceApi.getAll().then((instances) => {
        this.instances = instances.data;
      })
    }

    get stages() {
      if (this.instances == null) {
        return [];
      }

      let stages = this.instances.map(i => i.stage);
      return [...new Set(stages)];
    }

    get environments() {
      if (this.instances == null) {
        return [];
      }

      let environments = new Map<String, Environment>();
      for (const instance of this.instances) {
        if (!environments.has(instance.environment)) {
          const environment = new Environment(instance.environment);
          environments.set(instance.environment, environment);
        }

        let environment = environments.get(instance.environment)!!;
        environment.setRelease(instance.stage, instance.currentReleaseName);
      }

      return Array.from(environments.values());

    }
  }
</script>

<style lang="sass" scoped>
  .clickable
    cursor: pointer
</style>
