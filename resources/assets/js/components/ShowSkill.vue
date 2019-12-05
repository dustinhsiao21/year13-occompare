<template>
<div class="text-left">
    <div v-for="(skill, index) in occupationArray" :key="index">
        <h4>{{ skill.label }}</h4>
        <div class="progress">
            <div class="progress-bar" role="progressbar" :style="getProgressBarValue(skill.value)" :aria-valuenow="skill.value" aria-valuemin="0" aria-valuemax="100">{{ skill.value }}%</div>
        </div>
        <div class="description">
            <p>{{ skill.description }}</p>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        name: 'show-skill',
        props: {
            occupationArray: {
                default: []
            },
        },
        methods: {
            getProgressBarValue(value){
                return {
                    width: value + "%"
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .description {
        height: 80px;
    }
</style>