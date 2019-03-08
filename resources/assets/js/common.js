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
    }
  }
}