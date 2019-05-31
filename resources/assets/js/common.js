export const common = {
    methods: {
        processloadBSideBarItems: function() {
            this.$store.dispatch('loadBSideBarItems');
        },
        processloadBOperators: function() {
            this.$store.dispatch('loadBOperators');
        },
        processloadBRegion: function() {
            this.$store.dispatch('loadBRegion');
        },
        processloadBTypes: function() {
            this.$store.dispatch('loadBTypes');
        },
        processloadBCards: function(bSideBar, operator, province, city, type) {
            this.$store.dispatch('loadBCards', {
                bSideBar: bSideBar,
                operator: operator,
                province: province,
                city: city,
                type: type
            });
        },
        processLoadBChart: function(bSideBar, operator, city, type, card, province, timeDim) {
            this.$store.dispatch('loadBChart', {
                bSideBar: bSideBar,
                operator: operator,
                city: city,
                type: type,
                card: card,
                province: province,
                timeDim: timeDim
            });
        },
        processLoadCog: function() {
            this.$store.dispatch('loadBCog');
        },
        processEditCog: function(editedItem) {
            this.$store.dispatch('editCog', {
                editedItem: editedItem
            });
        },
        processDelCog: function(item) {
            this.$store.dispatch('delCog', {
                item: item
            });
        },
        processLoadBarChart: function(optionState) {
            this.$store.dispatch('loadBarChart', {
                optionState: optionState
            });
        },
        processLoadBubbleChart: function(optionState) {
            this.$store.dispatch('loadBubbleChart', {
                optionState: optionState
            });
        },
        processLoadPieChart: function(optionState) {
            this.$store.dispatch('loadPieChart', {
                optionState: optionState
            });
        },
        processLoadTopCellTable: function(optionState) {
            this.$store.dispatch('loadTopCellTable', {
                optionState: optionState
            });
        },
    }
}