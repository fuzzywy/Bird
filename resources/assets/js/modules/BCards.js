import BCardsAPI from '../api/BCards.js';
export const BCards = {
    state: {
        bCardsStatus: 0,
        bCards: {}
    },
    actions: {
        loadBCards({ commit }, data) {
            commit('bCardsStatus', 1);
            BCardsAPI.getBCards(data.bSideBar, data.operator, data.province, data.city, data.type)
                .then(function(response) {
                    if (response.data != undefined) {
                        commit('bCards', response.data);
                        commit('bCardsStatus', 2);
                    } else {
                        commit('bCards', [response]);
                        commit('bCardsStatus', 3);
                    }
                })
                .catch(function() {
                    commit('bCards', ['Connection failed']);
                    commit('bCardsStatus', 3);
                });
        }
    },
    mutations: {
        bCardsStatus(state, status) {
            state.bCardsStatus = status;
        },
        bCards(state, bCards) {
            state.bCards = bCards;
        }
    },
    getters: {
        bCardsStatus(state) {
            return state.bCardsStatus;
        },
        bCards(state) {
            return state.bCards;
        }
    }
}