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
		bKpiCardStatus: 0,

		bScaleKpiCard: [],
		bScaleKpiCardStatus: 0,

		bLoadKpiCard: [],
		bLoadKpiCardStatus: 0
	},
	actions: {
		loadBKpiCardStatus( { commit }, data ) {
			commit( 'setbKpiCardStatus', 1 );
			BKpiCardAPI.getBKpiCard( data.type, data.city, data.overview )
			.then( (response) => {
				if ( response.data != undefined ) {
                    commit( 'setbKpiCard', response.data );
					commit( 'setbKpiCardStatus', 2 );
                }else {
                    commit( 'setbKpiCard', [ response ] );
					commit( 'setbKpiCardStatus', 3 );
                }
			} )
			.catch( function() {
				commit( 'setbKpiCard', [ 'Connection failed' ] );
				commit( 'setbKpiCardStatus', 3 );
			} )
		},

		loadBScaleKpiCard( { commit }, data ) {
			commit( 'setbScaleKpiCardStatus', 1 );
			BKpiCardAPI.getBScaleKpiCard( data.city, data.overview )
			.then( (response) => {
				if ( response.data != undefined ) {
                    commit( 'setbScaleKpiCard', response.data );
					commit( 'setbScaleKpiCardStatus', 2 );
                }else {
                    commit( 'setbScaleKpiCard', [ response ] );
					commit( 'setbScaleKpiCardStatus', 3 );
                }
			} )
			.catch( function() {
				commit( 'setbScaleKpiCard', [ 'Connection failed' ] );
				commit( 'setbScaleKpiCardStatus', 3);
			} )
		},

		loadBLoadKpiCard( { commit }, data ) {
			commit( 'setbLoadKpiCardStatus', 1 );
			BKpiCardAPI.getBLoadKpiCard( data.city, data.overview )
			.then( (response) => {
				if ( response.data != undefined ) {
                    commit( 'setbLoadKpiCard', response.data );
					commit( 'setbLoadKpiCardStatus', 2 );
                }else {
                    commit( 'setbLoadKpiCard', [ response ] );
					commit( 'setbLoadKpiCardStatus', 3 );
                }
			} )
			.catch( function() {
				commit( 'setbLoadKpiCard', [ 'Connection failed' ] );
				commit( 'setbLoadKpiCardStatus', 3 );
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
		},

		setbScaleKpiCardStatus( state, status ) {
			state.bScaleKpiCardStatus = status;
		},
		setbScaleKpiCard( state, bScaleKpiCard ) {
			state.bScaleKpiCard = []
			state.bScaleKpiCard.splice(0, state.bScaleKpiCard.length)
        	state.bScaleKpiCard = bScaleKpiCard
		},

		setbLoadKpiCardStatus( state, status ) {
			state.bLoadKpiCardStatus = status;
		},
		setbLoadKpiCard( state, bLoadKpiCard ) {
			state.bLoadKpiCard = []
			state.bLoadKpiCard.splice(0, state.bLoadKpiCard.length)
			state.bLoadKpiCard = bLoadKpiCard
		}
	},
	getters: {
		getbKpiCardStatus( state ) {
			return state.bKpiCardStatus;
		},
		getbKpiCard:state=>{
			return state.bKpiCard;
		},

		getbScaleCardStatus( state ) {
			return state.bScaleKpiCardStatus
		},
		getbScaleCard:state=>{
			return state.bScaleKpiCard
		},

		getbLoadKpiCardStatus( state ) {
			return state.bLoadKpiCardStatus
		},
		getbLoadKpiCard:state=>{
			return state.bLoadKpiCard
		}
	}
}