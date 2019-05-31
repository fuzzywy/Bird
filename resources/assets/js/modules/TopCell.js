import TopCellAPI from '../api/TopCell.js';
export const TopCell = {
    state: {
        topCellStatus: 0,
        topCell: {}
    },
    actions: {
        loadTopCellTable({
            commit
        }, data) {
            commit('topCellStatus', 1);
            TopCellAPI.getTopCell(data.optionState)
                .then(function(response) {
                    if (response.data != undefined) {
                        commit('topCell', response.data);
                        commit('topCellStatus', 2);
                    } else {
                        commit('topCell', [response]);
                        commit('topCellStatus', 3);
                    }
                })
                .catch(function() {
                    commit('topCell', ['Connection failed']);
                    commit('topCellStatus', 3);
                });
        }
    },
    mutations: {
        topCellStatus(state, status) {
            state.topCellStatus = status;
        },
        topCell(state, topCell) {
            state.topCell = topCell;
        }
    },
    getters: {
        topCellStatus(state) {
            return state.topCellStatus;
        },
        topCell(state) {
            return state.topCell;
        }
    }
}