<template>
    <div>
        {{event.name}}
    </div>
</template>
<script>
export default {
    data() {
        return{
            event:{
                id:'',
                name:'',
                description:'',
                start:'',
                end:'',
                weight:'',
                image_id: '',
            }
        }
    },
    created(){
        this.fetchEventdata(window.location.pathname)
    },
    methods: {
        fetchEventdata(event_route){
            fetch('/api'+event_route)
            .then( res => res.json())
            .then( res => this.event = res.data )
        }
    }

}
</script>
