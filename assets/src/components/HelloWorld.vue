<template>
  <v-container>
    <v-layout
      text-center
      wrap
    >
      <v-flex mb-4>
        <h1 class="display-2 font-weight-bold mb-3">
          Welcome!
        </h1>
        <v-btn @click="openNow">
          click me
        </v-btn>
        <p>
          {{openText}}
        </p>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Prop, Vue } from "vue-property-decorator";

@Component
export default class HelloWorld extends Vue {
  @Prop() private msg!: string;
  public isOpen = false;

  openNow() {
    this.isOpen = true;
  }

  get openText() {
    return this.isOpen ? "yes" : "no";
  }
}
</script>
