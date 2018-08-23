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
		// props: ['name'],
		// template: '<li @click="handleClick">{{name}}</li>',
		data() {
			return {
				posts: [
					{ id: 1, Content: '指标概览', routertag: 'indexoverview' },
					{ id: 2, Content: '规模概览', routertag: 'scaleoverview' }
				]
			}
		},
		/*watch: {
			name: function(news, olds) {
				alert(news);
			}
		},*/
		methods: {
			leftClick: function (event) {
				this.bus.$emit('leftClick',event.target.name)
				this.bus.$emit('leftClickData', event.target.name)
				/*axios.get(event.target.name, {
			    	params: {
			    		name: event.target.textContent,
			    		route: event.target.name
			    	}
			    })
			    .then(function(response) {
			    	// this.bus.$emit('leftClickData', event.target.name)
			    })
			    .catch(function(error) {
			    	// console.log(error)
			    })*/
			}
		}
	}
</script>