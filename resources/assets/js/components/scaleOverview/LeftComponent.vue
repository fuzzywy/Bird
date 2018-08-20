<template>
	<div>
		<b-list-group-item 
			v-for='(item, index) in posts'
			:key='item.id'
			content="item.name"
			:index='item.id'
			@click='alert'
		>{{ item.name }}</b-list-group-item>
	</div>
</template>

<script>
	export default {
		// props: ['content'],
		// template: '<li @click="handleClick">{{content}}</li>',
		data() {
			return {
				posts: [
					{ id: 1, name: '指标概览' },
					{ id: 2, name: '规模概览' }
				]
			}
		},
		methods: {
			alert: function(event) {
				alert(event.target.textContent)
				// alert(index.target.innerHTML)
			}
		}
	}
</script>