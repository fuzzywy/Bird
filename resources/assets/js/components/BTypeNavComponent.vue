<style>
    .v-btn--active.primary {
        color: #fff !important;
        background-color: #2196f3 !important;
        border-color: #2196f3 !important
    }

    .v-btn--depressed.primary--text {
        color: #000 !important;
        opacity: 1;
        border-color: rgba(0, 0, 0, .26) !important
    }
</style>
<template>
    <div>
        <input style="display: none;" id="input" :loadData="loadData">
        <div class="text-xs-center" v-if="this.$store.getters.bTypesStatus!==2">
            <v-spacer></v-spacer>
            <v-progress-circular indeterminate color="blue"></v-progress-circular>
        </div>
        <div v-if="this.$store.getters.bTypesStatus===2">
            <v-flex xs12 sm6 class="py-2">
                <v-btn-toggle mandatory>
                    <v-btn v-for="(type, key) in types" :key="key" @click="typeFix(type.type)"
                        :outline="type.type!==buttonType" color="primary">
                        {{ type.name }}
                    </v-btn>
                </v-btn-toggle>
            </v-flex>
        </div>
    </div>
</template>
<script>
    import {
        common
    } from '../common.js';
    export default {
        mixins: [
            common
        ],
        data() {
            return {
                active: 0,
                type: 'lte',
                types: [
                    // { id: 0, type: 'eniq', name: 'ENIQ' },
                    // { id: 1, type: 'nbiot', name: 'NBIOT' },
                    // { id: 2, type: 'volte', name: 'VOLTE' },
                    // { id: 3, type: 'gsm', name: 'GSM' }
                ],
                typeDim: 'LTE-TDD',
                buttonType: 'lte'
            }
        },
        methods: {
            typeFix: function (type) {
                this.type = type;
                this.bus.$emit('type', {
                    type: this.type
                });
                this.buttonType = type;
            }
        },
        created: function () {
            this.processloadBTypes();
        },
        computed: {
            loadData: function () {
                switch (this.$store.getters.bTypesStatus) {
                    case 1:
                        break;
                    case 2:
                        this.types = this.$store.getters.bTypes;
                        break;
                    case 3:
                        break;
                    default:
                        break;
                }
            }
        }
    }
</script>