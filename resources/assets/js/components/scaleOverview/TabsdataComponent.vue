<template>
	<div class="row">
        <div 
            class="col-4" 
            style="text-align: center;" 
            v-for="post in posts"
            :key="post.id"
            @click="alert"
        >
            <br />
            {{ post.type }}
            <br />
            <i :class="post.class"></i>
            {{ post.data }}
        </div>
  </div>
</template>

<script>
    // Vue.component('tabsdata-component', require('./TabsdataComponent.vue'));
    // import tabsComponent from './TabsComponent.vue';
	export default {
        name: 'tabsdata',
        data() {
            return {
                posts: [
                    { id: 0, type: '无线接通率', data: '93%', class: 'icon-ali-jiantou_xiangxia' },
                    { id: 1, type: '无线掉线率', data: '1%', class: 'icon-ali-jianhao' },
                    { id: 2, type: '接入成功率', data: '99%', class: 'icon-ali-jiantou_xiangshang' },
                ],
                newtabs: {data: "LTE", city: "全省", overview: "indexoverview"}
            }
        },
        methods: {
            alert: function() {
                // alert(this.thisTitleTxt)
                // alert(this.posts[0].type)
                /*axios.get(event.target.name, {
                    params: {
                        name: event.target.textContent,
                        route: event.target.name
                    }
                })
                .then(function(response) {
                    console.log(response.data)
                })
                .catch(function(error) {
                    console.log(error)
                })*/
            }
        },
        created() {
            this.bus.$on('tabsclick', tabs => { 
                // console.log(tabs)
                // console.log(this.newtabs.city)
                if ( !Object.is(this.newtabs.city , tabs.city) || !Object.is(this.newtabs.data , tabs.data) || !Object.is(this.newtabs.overview , tabs.overview)) {
                    this.newtabs.city = tabs.city
                    this.newtabs.data = tabs.data
                    this.newtabs.overview = tabs.overview
                   // alert('111');
                   // return
                }
                // Object.is(this.newtabs.data , tabs.data)
                // this.newtabs.city = tabs.city
                // this.newtabs.data = tabs.data
                // this.newtabs.overview = tabs.overview
                // alert(Object.is(this.newtabs.overview , tabs.overview))
                // console.log(this.newtabs)
                axios.get('getTabs', {
                    params: tabs
                })
                .then(function(response) {
                    console.log(response.data)
                })
                .catch(function(error) {
                    console.log(error)
                })
            });
        }
    }
</script>