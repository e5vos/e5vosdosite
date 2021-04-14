<template>
    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;">
            <thead class="thead-dark">
                <tr><th scope="col" colspan="5">Előadások</th></tr>
                <tr>
                    <th scope="col" rowspan="2">Előadó</th>
                    <th scope="col" rowspan="2">Előadás címe</th>
                    <th scope="col">Foglalt helyek</th>
                    <th scope="col">Szabad helyek</th>
                    <th scope="col" rowspan="2">Megjelent</th>
                </tr>
                <tr>
                    <th colspan="2"><button class="btn btn-warning" v-on:click="fillUpAll()">Automatikus feltöltés</button></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="presentation in presentations" v-bind:key="presentation" >
                    <td>{{presentation.presenter}}</td>
                    <td>{{presentation.title}}</td>
                    <td>{{presentation.capacity}}</td>
                    <td><button class="btn btn-warning" v-on:click="fillup(presentation.id)" :disabled="presentation.capacity - presentation.occupancy<= 0">{{presentation.capacity - presentation.occupancy}}</button></td>
                    <td>N/A</td>
                </tr>
            </tbody>
        </table>
</template>
<script>

export default {
    data() {
        return {
            selected_slot: '',
            selected_slot_before: '',
            presentations: [],
            presentation:{
                id:'',
                slot:'',
                presenter:'',
                title:'',
                description:'',
                location:'',
                capacity:'',
                code:'',
                occupancy: '',
            },
        }
    },
    created() {
        this.changeSlot(1)
        setInterval(this.changeSlot(selected_slot),1000)
    },
    methods: {
        changeSlot(slot){
            this.selected_slot_before = slot
            fetch('/api/e5n/presentations/'+slot)
            .then(res => res.json())
            .then(res => {
                this.presentations=res.data
                this.selected_slot=slot
            })
        },
        fillUp(presid){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"},
                body: JSON.stringify({
                    presentation: presid
                })
            }
            fetch('api/e5n/presadmin/fillup/',requestOptions)
            .then(this.changeSlot(this.selected_slot))
        },
        fillUpAll(){
            const requestOptions = {
                method: "POST",
                headers:{"Content-Type":"application/json"}
            }
            fetch('api/e5n/presadmin/fillupall',requestOptions)
            .then(this.changeSlot(this.selected_slot))
        }
    }
}
</script>
