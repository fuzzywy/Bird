<template>
	<div>
		<b-list-group-item button
			v-for='(item, index) in posts'
			:key='item.id'
			:index='item.id'
			:name='item.routertag'
			@click='leftClick'
		>{{ item.Content }}</b-list-group-item>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				posts: [
					{ id: 1, Content: '指标概览', routertag: 'indexoverview' },
					{ id: 2, Content: '规模概览', routertag: 'scaleoverview' }
				]
			}
		},
		methods: {
			leftClick: function (event) {
				this.bus.$emit('leftClick',event.target.name)
				this.bus.$emit('leftClickData', event.target.name)
			}
		}
	}
</script>