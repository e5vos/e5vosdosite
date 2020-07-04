<template>
<div>
    <div class="py-2 btn-group d-flex justify-content-center" role="group" style="text-align:center">
        <button v-on:click="changeSlot(1)" v-bind:class="{active: selected_slot==1 }" type="button" class="btn btn-secondary">1. előadássáv</button>
        <button v-on:click="changeSlot(2)" v-bind:class="{active: selected_slot==2 }" type="button" class="btn btn-secondary">2. előadássáv</button>
        <button v-on:click="changeSlot(3)" v-bind:class="{active: selected_slot==3 }" type="button" class="btn btn-secondary">3. előadássáv</button>
    </div>
    <table class="table table-light table-bordered" style="text-align:center;width:100%;table-layout: auto;overflow-wrap: break-word;">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Előadó</th>
                        <th scope="col">Előadás címe</th>
                        <th scope="col">Előadás rövid leírása</th>
                        <th scope="col">Szabad helyek száma</th>
                    </tr>
                </thead>
        <tbody>
            <tr>
                <td colspan="4" style="font-weight:600;font-size:24px;">Az általad választott előadás:</td>
            </tr>
            <tr style="background-color:rgb(233, 233, 233)">
                <td>{{selected_presentation.presenter}}</td>
                <td>{{selected_presentation.title}}</td>
                <td>{{selected_presentation.description}}</td>
                <td><button class="btn btn-secondary" disabled>{{Math.max(selected_presentation.capacity-selected_presentation.occupancy,0)}}</button></td>
            </tr>
            <tr><td colspan=4><button class="btn btn-danger">Jelentkezés törlése</button></td></tr>
            <tr><td class="py-0 my-0" colspan=4><hr class="py-0 my-0" style="border-top: 1px solid black;"></td></tr>
            <tr v-for="presentation in presentations" v-bind:key="presentation.id">
                <td>{{presentation.presenter}}</td>
                <td>{{presentation.title}}</td>
                <td>{{presentation.description}}</td>
                <td><button class="btn btn-success" disabled>{{Math.max(presentation.capacity-presentation.occupancy,0)}}</button></td>
            </tr>
        </tbody>
    </table>
</div>
</template>


<script>
export default {
    data(){
        return{
            presentations: [],
            selected_slot: '',
            selected_presentation: [],
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
            diakcode: '',
            omcode: '',
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
                console.log(this.presentations)
            })
        },
        fetchSelected(slot){
            if(this.diakcode ==='' || this.omcode ==='') return;
            fetch('/api/e5n/presentations/selected/'+this.diakcode+'/'+this.omcode+'/'+slot)
            .then(res =>res.json())
            .then(res =>{
                this.selected_presentation=res.data
            })
        },
        changeSlot(slot){
            this.fetchSlot(slot)
            this.fetchSelected(slot)
            this.selected_slot=slot
        }
    }
}
</script>
