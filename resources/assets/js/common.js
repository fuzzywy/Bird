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
    }
  }
}