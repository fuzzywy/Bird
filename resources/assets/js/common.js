export const common = {
  methods: {
    processloadBSideBarItems: function() {
      this.$store.dispatch( 'loadBSideBarItems' );
    },
    processloadBOperators: function() {
      this.$store.dispatch( 'loadBOperators' );
    },
    processloadBRegion: function() {
      this.$store.dispatch( 'loadBRegion' );
    },
    processloadBTypes: function() {
      this.$store.dispatch( 'loadBTypes' );
    },
    processloadBCards: function() {
      this.$store.dispatch( 'loadBCards' );
    },
    processLoadBChart: function(bSideBar, operator, city, type, card, province, timeDim) {
      this.$store.dispatch( 'loadBChart', {
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
      this.$store.dispatch( 'loadBCog' );
    },
    processEditCog: function(editedItem) {
      this.$store.dispatch( 'editCog', {
        editedItem: editedItem
      });
    },
    processDelCog: function(item) {
      this.$store.dispatch( 'delCog', {
        item: item
      });
    }
  }
}