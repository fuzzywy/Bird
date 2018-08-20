<template>
	<b-card no-body>
        <b-tabs card>
			<b-tab no-body 
				v-for='post in posts'
				:key='post.id'
				:title='post.type'
				@click='alert'>
				<tabsdata-component></tabsdata-component>
				<b-card-footer>{{ post.name }}</b-card-footer>
			</b-tab>
		</b-tabs>
    </b-card>
</template>

<script>
	export default {
		data() {
			return {
				posts: [
					{ id: 0, type:'LTE', name: "网络概览" },
					{ id: 1, type:'VOLTE', name: '网络概览' },
					{ id: 2, type:'NB-IOT', name: '网络概览' },
					{ id: 3, type:'GSM', name: '网络概览' }
				]
			}
		},
		methods: {
			alert: function() {
				console.log(this)
			}
		}
	}
</script>