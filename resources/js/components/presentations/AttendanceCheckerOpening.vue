<template>
    <div>
        <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
            <button v-for="i in 3" :key="i" v-on:click="changeSlot(i)" v-bind:class="{active: selected_slot== i }" type="button" class="btn btn-secondary">{{i}}. előadássáv</button>
        </div>
        <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
            <tr v-for="presentation in presentations" v-bind:key="presentation.id">
                <td>{{presentation.title}}</td>
                <td>
                    <a v-bind:href="'/e5n/presentations/attendance/'+presentation.code">
                        <button class="btn btn-primary">Jelenléti ív</button>
                    </a>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    data(){
        return{
            presentations: [],
            selected_slot: '',
            presentation:{
                id:'',
                slot:'',
                title:'',
                description:'',
                location:'',
                capacity:'',
                code:'',
                occupancy: '',
            },

        }
    },
    created(){
        this.changeSlot(1)
    },
    methods: {
        fetchSlot(slot){
            fetch('/api/e5n/presentations/'+slot)
            .then(res => res.json())
            .then(res => {
                this.presentations=res.data
            })
        },

        changeSlot(slot){
            this.fetchSlot(slot)
            this.selected_slot=slot
        },
    }
}
</script>
