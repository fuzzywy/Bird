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
	export default {
        data() {
            return {
                posts: [
                    { id: 0, type: '无线接通率', data: '93%', class: 'icon-ali-jiantou_xiangxia' },
                    { id: 1, type: '无线掉线率', data: '1%', class: 'icon-ali-jianhao' },
                    { id: 2, type: '接入成功率', data: '99%', class: 'icon-ali-jiantou_xiangshang' },
                ]
            }
        },
        methods: {
            alert: function() {
                alert(this.posts[0].type)
            }
        }
    }
</script>