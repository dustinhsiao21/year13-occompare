<template>
    <selectize v-model="selected" :disabled="loading">
        <option :value="null">Please select</option>
        <option v-for="(occupation, index) in occupations" :key="index" :value="occupation.code">{{ occupation.title }}</option>
    </selectize>
</template>

<script>
    import Selectize from 'vue2-selectize';

    export default {
        name: 'select-occupation',
        components: {
            Selectize
        },
        data() {
            return {
                selected: null
            };
        },
        props: {
            value: {
                default: null
            },
            occupations: {
                default: []
            },
            loading: {
                default: false,
            }
        },
        watch: {
            selected() {
                this.$emit('input', this.selected);
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '~selectize/dist/css/selectize.default.css';
</style>