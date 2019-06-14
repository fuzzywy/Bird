<template>
    <div style="background-color: #fff">
        <input style="display: none;" id="input" :loadTopCellData="loadTopCellData">
        <v-flex class="text-center"
            style="font-size:16px;height:44px;box-shadow: 0 2px 1px -1px rgba(0,0,0,.2),0 1px 1px 0 rgba(0,0,0,.14),0 1px 3px 0 rgba(0,0,0,.12)!important">
            TOP30小区详情
        </v-flex>
        <v-data-table :id="id" :headers="headers" :items="optionData" :loading="loading" class="elevation-1"
            style="height: 356px;overflow: auto">
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.day }}</td>
                <td class="text-xs-center">{{ props.item.hour }}</td>
                <td class="text-xs-center">{{ props.item.city }}</td>
                <td class="text-xs-center">{{ props.item.location }}</td>
                <td class="text-xs-center">{{ props.item.failTimes }}</td>
            </template>
        </v-data-table>
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
        props: {
            optionState: {}
        },
        data() {
            return {
                id: 'containerTopCell',
                loading: true,
                optionData: [],
                headers: [{
                        text: 'day_id',
                        value: 'day',
                        sortable: false
                    },
                    {
                        text: 'hour_id',
                        value: 'hour',
                        sortable: false
                    },
                    {
                        text: 'city',
                        value: 'city',
                        sortable: false
                    },
                    {
                        text: 'cell',
                        value: 'location',
                        sortable: false
                    },
                    {
                        text: 'failTimes',
                        value: 'failTimes',
                        sortable: false
                    }
                ]
            }
        },
        mounted() {},
        methods: {},
        watch: {
            optionState() {
                this.loading = true;
                this.processLoadTopCellTable(this.optionState);
            },
            optionData(val) {
                // console.log(val);
                this.loading = false;

            },
        },
        computed: {
            loadTopCellData: function () {
                switch (this.$store.getters.topCellStatus) {
                    case 1:
                        break;
                    case 2:
                        this.optionData = this.$store.getters.topCell;
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