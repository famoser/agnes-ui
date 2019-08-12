<template>
    <button class="btn" @click="$emit('create-release')">Create Release</button>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from "vue-property-decorator";

    @Component
    export default class CreateRelease extends Vue {
        @Prop() private msg!: string;
    }
</script>