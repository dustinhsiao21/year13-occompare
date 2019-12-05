<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-center">
                <div class="form">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <label>Occupation 1</label>
                            <select-occupation v-model="occupation_1" :occupations="occupations" :loading="loading"></select-occupation>
                        </div>
                        <div class="col-md-5">
                            <label>Occupation 2</label>
                            <select-occupation v-model="occupation_2" :occupations="occupations" :loading="loading"></select-occupation>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-danger btn-block mt-4" @click.prevent="compare" :disabled="!occupation_1 || !occupation_2 || loading">
                                <template v-if="loading">
                                    <i class="fa fa-refresh fa-spin"></i>
                                </template>
                                <template v-else>
                                    Compare
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <template v-if="errorMessage !== null">
                <div class="col-12 text-center text-danger">
                    {{ errorMessage }}
                </div>
            </template>
            <template v-else-if="match !== null && !loading">
                <div class="col-12 text-center">
                    <h1>Matching Percentage: {{ match }} %</h1>
                </div>
            </template>
            <template v-else-if="match === null && !loading">
                <div class="col-12 text-center">
                    Please select two Occupations from above and click Compare.
                </div>
            </template>
            <template v-else-if="loading">
                <div class="col-12 text-center">
                    Please wait...<i class="fa fa-refresh fa-spin"></i>
                </div>
            </template>
        </div>
        <div class="row" v-show="match > 0">
            <div class="col-12 text-center">
                <div class="form">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <h3>Occupation 1's Skills</h3>
                            <show-skill :occupationArray="occupationOneSkills"></show-skill>
                        </div>
                        <div class="col-md-5">
                            <h3>Occupation 2's Skills</h3>
                            <show-skill :occupationArray="occupationTwoSkills"></show-skill>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SelectOccupation from '../components/form-controls/SelectOccupation';
    import ShowSkill from '../components/ShowSkill';

    export default {
        name: 'home-page',
        components: {
            SelectOccupation,
            ShowSkill
        },
        async created() {
            let response = await this.axios.get('/api/occupations');
            this.occupations = response.data;
        },
        data() {
            return {
                loading: false,
                occupation_1: null,
                occupation_2: null,
                match: null,
                occupations: [],
                errorMessage: null,
                occupationOneSkills: [],
                occupationTwoSkills: [],
            }
        },
        methods: {
            compare() {
                this.loading = true;
                this.errorMessage = null;
                this.match = null,
                this.occupationOneSkills = [],
                this.occupationTwoSkills = [],
                this.axios.post('/api/compare', {
                    occupation_1: this.occupation_1,
                    occupation_2: this.occupation_2
                }).then((response) => {
                    this.loading = false;
                    this.match = response.data.match;
                    this.occupationOneSkills = response.data.occupation_1;
                    this.occupationTwoSkills = response.data.occupation_2;
                }).catch((error) => {
                    this.errorMessage = error.response.data.message;
                    this.loading = false;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .form-group {
        label {
            font-size: 0.8rem;
            text-align: left;
            display: block;
            margin-bottom: 0.2rem
        }
    }
</style>