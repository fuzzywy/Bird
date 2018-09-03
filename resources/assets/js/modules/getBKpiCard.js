/*
 |-------------------------------------------------------------------------------
 | VUEX modules/getBKpiCard.js
 |-------------------------------------------------------------------------------
 | The Vuex data store for the getBKpiCard
 */
import BKpiCardAPI from "../api/getBKpiCard.js";

export const bKpiCard = {
	state: {
		bKpiCard: [],
		bKpiCardStatus: 0
	},
	actions: {
		loadBKpiCardStatus( { commit }, data ) {
			commit( 'setbKpiCardStatus', 1 );
			BKpiCardAPI.getBKpiCard( data.type, data.city, data.overview )
			.then( (response) => {
				commit( 'setbKpiCard', response.data );
				commit( 'setbKpiCardStatus', 2 );
			} )
			.catch( function() {
				commit( 'setbKpiCard', {} );
				commit( 'setbKpiCardStatus', 3 );
			} )
		}
	},
	mutations: {
		setbKpiCardStatus( state, status ) {
			state.bKpiCardStatus = status;
		},
		setbKpiCard( state, bKpiCard ) {
			state.bKpiCard = []
			state.bKpiCard.splice(0, state.bKpiCard.length)
        	state.bKpiCard = bKpiCard
		}
	},
	getters: {
		getbKpiCardStatus( state ) {
			return state.bKpiCardStatus;
		},
		getbKpiCard:state=>{
			return state.bKpiCard;
		}
	}
}