<template>
    <div id="app">
        <CreateRelease @create-release="openReleaseDialog"></CreateRelease>
        <p>{{currentlyOpen}}</p>
        <img alt="Vue logo" src="./assets/logo.png"/>
        <HelloWorld msg="Welcome to Your Vue.js + TypeScript App"/>
    </div>
</template>

<script lang="ts">
    import {Component, Vue} from "vue-property-decorator";
    import HelloWorld from "./components/HelloWorld.vue";
    import CreateRelease from "@/components/CreateRelease.vue";

    @Component({
        components: {
            CreateRelease,
            HelloWorld
        }
    })
    export default class App extends Vue {
        isOpen: Boolean = false;

        openReleaseDialog() {
            this.isOpen = true;
        }

        get currentlyOpen() {
            return this.isOpen ? "yes" : "no";
        }
    }
</script>

<style lang="scss">
    #app {
        font-family: "Avenir", Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
